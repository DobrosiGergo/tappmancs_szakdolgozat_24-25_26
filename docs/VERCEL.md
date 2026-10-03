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
APP_URL=https://tappmancs-szakdolgozat.hu
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

# A domain csak HTTPS-en szolgál ki, így a cookie-t ne bízzuk auto-detectre
SESSION_SECURE_COOKIE=true

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

## 4. Saját domain: tappmancs-szakdolgozat.hu

A Vercel Hobby tieren a custom domain ingyenes, a TLS tanúsítványt (Let's
Encrypt) a Vercel automatikusan kezeli és újítja.

1. Vercel → Project → Settings → Domains → add `tappmancs-szakdolgozat.hu`.
2. Add hozzá a `www.tappmancs-szakdolgozat.hu`-t is, és állítsd be
   **redirectnek az apexre** — így egy kanonikus hoston fut minden, és a
   session cookie sem hasad szét két host között.
3. Állítsd be a DNS rekordokat a `.hu` regisztrátorod felületén.

### DNS rekordok

| Típus | Név | Érték |
|---|---|---|
| A | `@` (apex) | `76.76.21.21` |
| CNAME | `www` | a Vercel által kiírt `cname.vercel-dns…` érték |

> **Az értékeket a Vercel felületéről olvasd ki, ne innen.** A Vercel saját
> dokumentációja is jelzi, hogy a rekordok projektspecifikusak lehetnek, és a
> CNAME célpontja több változatban él (`cname.vercel-dns.com`,
> `cname.vercel-dns-0.com`). A Domains fül pontosan kiírja, mi kell; CLI-ből
> `vercel domains inspect tappmancs-szakdolgozat.hu`.

Apex domainre **nem lehet CNAME-et** tenni (DNS-standard), ezért ott A rekord kell.

### CAA rekord

Ha a domainen van CAA rekord, engedned kell a Let's Encryptet, különben a
Vercel nem tud tanúsítványt kiállítani:

```
0 issue "letsencrypt.org"
```

Ha nincs CAA rekordod egyáltalán, nincs mit tenni — az bármely CA-t engedi.

### HTTPS a proxy mögött

A Vercel terminálja a TLS-t, és a sémát csak az `X-Forwarded-Proto` headerben
adja tovább. Ezért a `bootstrap/app.php`-ban be van állítva a
`trustProxies(at: '*')` — enélkül a Laravel HTTP-nek látná a kérést, és
`http://` URL-eket generálna egy HTTPS-es oldalon (blokkolt asseteket és
lefokozott login-redirecteket okozva).

A wildcard itt azért helyes, mert a Vercel proxy IP-i nem fix range-ben vannak,
és az origin **csak** a proxyn keresztül érhető el. Saját VM-en ezt nem így
kellene beállítani. Lefedve: `tests/Feature/ProxiedHttpsTest.php`.

## 5. Adatbázis inicializálása

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

## 6. Cron

A `vercel.json` két napi cront definiál (Hobby tieren ez a maximum, és csak
napi felbontás van):

| Útvonal | Parancs | Idő |
|---|---|---|
| `/cron/prune-uploads` | `uploads:prune` | 03:00 UTC |
| `/cron/demo-reset` | `demo:reset` | 04:00 UTC |

A végpontok `CRON_SECRET` nélkül 404-et adnak (lásd `tests/Feature/Routes/CronRoutesTest.php`).

## 7. E2E a production ellen

```bash
cd app-e2e
E2E_BASE_URL=https://tappmancs-szakdolgozat.hu npm run test:e2e:prod
```

Cold start miatt az első kérés lassabb — ha flaky lesz, emeld a Playwright
timeoutot, ne a tesztet lazítsd.

## Amit az AWS-ről le lehet kapcsolni

A migráció után az EC2 instance és a hozzá tartozó Elastic IP / security group
törölhető. S3 és RDS nem volt használatban. A `secrets.EC2_SSH_KEY` és
`secrets.EC2_HOST` GitHub secretek törölhetők.
