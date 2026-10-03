# Vercel deploy (AWS EC2 leváltása)

Ez a dokumentum a `main`-re való pushtól az élő oldalig tartó teljes beállítást írja le.
Az alkalmazás serverless functionként fut, ezért három külső szolgáltatás kell hozzá:

| Szerep | Szolgáltatás | Ingyenes keret |
|---|---|---|
| Hosting | Vercel Hobby | ingyenes, **csak nem-kereskedelmi** használatra (szakdolgozat belefér) |
| Adatbázis | Neon Postgres | ingyenes tier |
| Képtárolás | Cloudflare R2 | 10 GB ingyen |

> **Az `AWS_*` env változókról:** az R2 az S3 *protokollt* beszéli, ezért a Laravel `s3`
> diskje és a `league/flysystem-aws-s3-v3` csomag kezeli. A változónevek emiatt
> `AWS_`-sel kezdődnek, de **nincs AWS-fiók a láncban** — a végpont a Cloudflare-é.

## Miért nem lehetett „először a hosting, utána a Postgres"

A Vercel functionjeinek a filesystemje a `/tmp`-en kívül csak olvasható, az pedig
cold startnál eldobódik. Ezért egyszerre kellett megtörténnie:

- **SQLite → Postgres.** Egy fájl alapú adatbázis itt nem tud működni.
- **Képfeltöltés → object storage.** A `storage/app/public` + `public/storage`
  symlink megszűnik; a feltöltés disket az `UPLOADS_DISK` határozza meg.
- **Session és cache `file` → `database`.** Mindkettőhöz van migráció.
- **Scheduler → Vercel Cron.** Nincs crontab, ami a `schedule:run`-t hívná.

## 1. Neon Postgres

1. Hozz létre egy projektet a [neon.tech](https://neon.tech)-en.
2. Másold ki a connection stringet, és bontsd szét a lenti env változókra.
3. A `sslmode=require` kötelező.

## 2. Cloudflare R2

1. R2 bucket létrehozása (pl. `tappmancs-uploads`).
2. **Public access** bekapcsolása — az `r2.dev` publikus URL kell a képek kiszolgálásához.
3. S3 API token generálása (Object Read & Write).
4. Az `AWS_URL` a bucket publikus base URL-je, az `AWS_ENDPOINT` az S3 API végpont.

## 3. Vercel projekt

1. Importáld a GitHub repót a Vercelre.
2. **Root Directory: `app`** — a repó monorepo, a Laravel az `app/` alatt van.
3. Framework Preset: **Other**.
4. A build commandot a `app/vercel.json` adja (`npm install && npm run build`);
   a composer installt a `vercel-php` runtime végzi.

### Env változók (Vercel → Settings → Environment Variables)

```dotenv
APP_NAME=Tappmancs
APP_ENV=production
APP_KEY=                      # php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://<project>.vercel.app
APP_LOCALE=hu
APP_FALLBACK_LOCALE=hu

# Serverless: a filesystem read-only, ezért stderr-re logolunk
LOG_CHANNEL=stderr
LOG_LEVEL=warning

# Neon Postgres
DB_CONNECTION=pgsql
DB_HOST=<neon-host>
DB_PORT=5432
DB_DATABASE=<db>
DB_USERNAME=<user>
DB_PASSWORD=<pass>
DB_SSLMODE=require

# Nem lehet "file" — nincs írható, megosztott filesystem
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# Feltöltések: Cloudflare R2 (S3-kompatibilis)
UPLOADS_DISK=s3
AWS_ACCESS_KEY_ID=<r2-access-key>
AWS_SECRET_ACCESS_KEY=<r2-secret>
AWS_DEFAULT_REGION=auto
AWS_BUCKET=tappmancs-uploads
AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
AWS_URL=https://<public-bucket-url>
AWS_USE_PATH_STYLE_ENDPOINT=false

# A Vercel Cron ezt küldi Bearer tokenként
CRON_SECRET=<generálj egy hosszú random stringet>
```

`QUEUE_CONNECTION=sync`, mert az alkalmazás nem dispatch-el queue jobot
(ellenőrizve: nincs `ShouldQueue` és nincs `dispatch()`), így nem kell worker.

## 4. Adatbázis inicializálása

A migrációkat **nem** a serverless function futtatja, hanem a GitHub Actions
(`.github/workflows/deploy.yml`), mert a runnerben van PHP és eléri a Neont.

Első alkalommal a seedet kézzel indítsd:

```
Actions → Deploy → Run workflow → seed: true
```

Ez lefuttatja a `migrate --force`, `db:seed --force` és `demo:reset` parancsokat.
Utána minden push csak a `migrate --force`-ot futtatja.

GitHub repository secretek (Settings → Secrets → Actions):
`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_KEY`

## 5. Cron

A `vercel.json` két napi cront definiál (Hobby tieren ez a maximum, és csak
napi felbontás van):

| Útvonal | Parancs | Idő |
|---|---|---|
| `/cron/prune-uploads` | `uploads:prune` | 03:00 UTC |
| `/cron/demo-reset` | `demo:reset` | 04:00 UTC |

A végpontok `CRON_SECRET` nélkül 404-et adnak (lásd `tests/Feature/Routes/CronRoutesTest.php`).

## 6. E2E a production ellen

```bash
cd app-e2e
E2E_BASE_URL=https://<project>.vercel.app npm run test:e2e:prod
```

Cold start miatt az első kérés lassabb — ha flaky lesz, emeld a Playwright
timeoutot, ne a tesztet lazítsd.

## Amit az AWS-ről le lehet kapcsolni

A migráció után az EC2 instance és a hozzá tartozó Elastic IP / security group
törölhető. S3 és RDS nem volt használatban. A `secrets.EC2_SSH_KEY` és
`secrets.EC2_HOST` GitHub secretek törölhetők.
