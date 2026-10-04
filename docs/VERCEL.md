# Migráció: AWS EC2 → Vercel + Aiven MySQL + Cloudflare R2

Teljes, lépésenkénti útmutató a nulláról az élő oldalig, majd az AWS leállításáig.

## Hol tartasz

A kódoldal készen van és verifikálva; ami hátravan, az jórészt fiókbeállítás.

```
KÉSZ (commitolva, de NINCS pusholva)
  [x] Serverless entrypoint (api/index.php, vercel.json, .vercelignore)
  [x] MySQL-re állítva, dev és prod egyaránt
  [x] Feltöltés object storage-ra (UploadDisk seam)
  [x] Session és cache database driverre
  [x] Cron végpontok CRON_SECRET védelemmel
  [x] trustProxies a Vercel proxy mögé
  [x] Aiven CA cert a repóban (database/certs/ca.pem)
  [x] deploy.yml: EC2 helyett migráció a MySQL ellen

RÁD VÁR
  [ ] 1.  Lokális MySQL + .env            -> 1. lépés
  [ ] 2.  Aiven service adatai            -> 2. lépés
  [ ] 3.  R2 bucket + public URL + token  -> 3. lépés
  [ ] 4.  APP_KEY generálás               -> 4. lépés
  [ ] 5.  GitHub secretek (6 db)          -> 5. lépés
  [ ] 6.  Vercel projekt + env változók   -> 6. lépés
  [ ] 7.  Domain + DNS                    -> 7. lépés
  [ ] 8.  git push                        -> 8. lépés
  [ ] 9.  Első seed (kézi workflow)       -> 9. lépés
  [ ] 10. Ellenőrzés                      -> 10. lépés
  [ ] 11. AWS leállítása                  -> 11. lépés
```

### Tartalom

- [Mi változik és miért](#mi-változik-és-miért)
- [0. Előkészítés](#0-előkészítés)
- [1. Lokális fejlesztés](#1-lokális-fejlesztés-dev-adatbázis)
- [2. Aiven MySQL](#2-aiven-mysql)
- [3. Cloudflare R2](#3-cloudflare-r2)
- [4. App key](#4-app-key)
- [5. GitHub secrets](#5-github-secrets)
- [6. Vercel projekt](#6-vercel-projekt)
- [7. Domain](#7-domain-tappmancs-szakdolgozathu)
- [8. Push](#8-push)
- [9. Első seed](#9-első-seed)
- [10. Ellenőrzés](#10-ellenőrzés)
- [11. AWS leállítása](#11-az-aws-leállítása)
- [Hibakeresés](#hibakeresés)
- [Visszaállás](#visszaállás-rollback)
- [Szolgáltató-váltás később](#szolgáltató-váltás-később)

---

## Mi változik és miért

### Előtte (AWS)

```
GitHub push -> Actions -> SSH -> EC2 instance
                                  ├─ nginx + PHP-FPM
                                  ├─ SQLite fájl a diszken
                                  └─ feltöltött képek: storage/app/public
                                     (symlink: public/storage)
```

### Utána (Vercel)

```
GitHub push ─┬─> Vercel build -> serverless function (PHP 8.4)
             │                    └─ /tmp (ephemeral, cold startnál törlődik)
             └─> Actions -> migrate --force ──┐
                                              v
                           Aiven MySQL   <── DB, session, cache
                           Cloudflare R2 <── feltöltött képek
```

| Szerep | Szolgáltatás | Költség |
|---|---|---|
| Hosting | Vercel Hobby | ingyenes, **csak nem-kereskedelmi** célra (szakdolgozat belefér) |
| Adatbázis | Aiven for MySQL | ingyenes, örökre, bankkártya nélkül |
| Képtárolás | Cloudflare R2 | 10 GB ingyen |

### Miért nem lehetett „először a hosting, utána az adatbázis"

A Vercel functionjeinek a filesystemje a `/tmp`-en kívül **csak olvasható**, és a
`/tmp` is eldobódik cold startnál. Ebből következik, hogy egyszerre kellett
megtörténnie:

| Változás | Miért kényszer |
|---|---|
| SQLite → MySQL | fájl alapú DB-t nem lehet írni, és cold startnál elvesznék |
| Lokális feltöltés → R2 | a `storage/app/public` írása nem lehetséges, a `public/storage` symlink értelmezhetetlen |
| Session/cache `file` → `database` | nincs írható, kérések között megosztott filesystem |
| Scheduler → Vercel Cron | nincs crontab, ami a `schedule:run`-t hívná |

### Mit nem kellett megváltoztatni

- **Queue worker nincs.** Ellenőrizve: a kódbázisban nincs `dispatch()` és nincs
  `ShouldQueue`, a `WorkerAssigned` notification szinkron. Ezért
  `QUEUE_CONNECTION=sync` és nem kell futó worker — ez a legnagyobb serverless
  blokkoló, és szerencsére nem áll fenn.
- **Az alkalmazás logikája.** A feltöltés diskje egyetlen helyen dől el
  (`App\Support\UploadDisk`), amit az `UPLOADS_DISK` env vezérel.

### Előzetes verifikáció

Amit lokálisan már levalidáltam, hogy ne a deploynál derüljön ki:

| Ellenőrzés | Eredmény |
|---|---|
| 11 migráció MySQL-en | tisztán lefut |
| Seederek + `demo:reset` MySQL-en | lefut |
| Teljes PHP tesztszuite MySQL-en | **290/290** (MySQL 9.6, ellenőrzött driver) |
| E2E szuite | **46/46** (chromium + firefox) |
| Proxy mögötti HTTPS | lefedve, és ellenőrizve, hogy a fix nélkül elbukik |
| CA cert feloldása | lefedve (`MysqlSslCaTest`) |

**Amit nem tudtam verifikálni:** magát a Vercel deployt. A legvalószínűbb
buktató a statikus asset routing — lásd [Hibakeresés](#hibakeresés).

---

## 0. Előkészítés

Mielőtt bármihez hozzányúlnál:

```bash
cd ~/Projects/tappmancs/tappmancs_szakdolgozat_24-25_26
git log --oneline -5       # a migrációs commitok lokálisan vannak, nincsenek pusholva
```

**Ne pusholj**, amíg a 8. lépésig el nem jutottál — a push elindítja a migrációs
workflow-t, ami hozzáférések nélkül elhasal.

### Jelöld meg a visszaállási pontot

A migráció előtti utolsó állapot a `5d36b46` commit, ami **már fel van pusholva**.
Tag-eld, hogy a rollback ne hash-ekre támaszkodjon:

```bash
git tag pre-vercel 5d36b46
git push origin pre-vercel
```

### Van-e éles adat az EC2-n?

A terv friss seed, tehát a prod adatbázis tartalma **nem** kerül át. Ha van olyan
tartalom, amit nem akarsz elveszíteni (valódi menhely, kisállat, feltöltött kép),
mentsd le **most**, mert a 11. lépés után már nem lesz honnan:

```bash
ssh ubuntu@<EC2_HOST>
cd /var/www/tappmancs/app
cp database/database.sqlite ~/tappmancs-backup.sqlite       # adatbázis
tar czf ~/tappmancs-uploads.tar.gz storage/app/public       # feltöltött képek
exit

scp ubuntu@<EC2_HOST>:~/tappmancs-backup.sqlite ./
scp ubuntu@<EC2_HOST>:~/tappmancs-uploads.tar.gz ./
```

Ha csak seedelt demo-adat van (valószínű, mert a régi workflow minden pushnál
`db:seed`-et és `demo:reset`-et futtatott), ez kihagyható.

---

## 1. Lokális fejlesztés (dev adatbázis)

A dev és a prod ugyanazt a motort használja, hogy ne térjenek el egymástól:

```bash
mysql -u root -e "create database tappmancs character set utf8mb4 collate utf8mb4_unicode_ci"

cd app
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

Az `app/.env.example` már MySQL-re van állítva (`127.0.0.1:3306`, db: `tappmancs`,
user: `root`, üres jelszó). Ha a lokális MySQL-ednek van root jelszava, írd be a
`DB_PASSWORD`-be.

**Ellenőrzés:**

```bash
php artisan test                      # 290 passed
npm run dev                           # másik terminálban
cd ../app-e2e && npm run test:e2e     # 46 passed
```

### Kezelőfelület (dev és prod egy kliensből)

| Eszköz | Megjegyzés |
|---|---|
| **MySQL Workbench** | Oracle official desktop appja, ingyenes |
| **TablePlus** | letisztult, macOS-en a legkényelmesebb (fizetős, van ingyenes limit) |
| **DBeaver** | ingyenes, minden platformon |
| Aiven webes konzol | böngészőből, telepítés nélkül |

Vegyél fel **két kapcsolatot** ugyanabban a kliensben:

| Kapcsolat | Host | Port | DB | User |
|---|---|---|---|---|
| `tappmancs-dev` | `127.0.0.1` | 3306 | `tappmancs` | `root` |
| `tappmancs-prod` | Aiven host | Aiven port | `defaultdb` | `avnadmin` |

A prod kapcsolatnál kapcsold be az **SSL**-t (az Aiven megköveteli), és add meg a
`app/database/certs/ca.pem` fájlt CA certként.

---

## 2. Aiven MySQL

### Service létrehozása

1. Regisztráció: [aiven.io](https://aiven.io) — **nem kell bankkártya**.
2. *Create service* → **MySQL**.
3. Cloud: bármelyik. **Régió: Európa** (pl. `google-europe-west1`) — a latencia
   miatt, mert minden kérés a Vercel functionből ide megy.
4. Plan: **Free**.
5. Service name: `tappmancs-db`.

A kiépítés 1–3 perc. Amíg `REBUILDING`, nem fogad kapcsolatot.

### Kapcsolódási adatok

A service *Overview* fülén:

| Mező | Env változó |
|---|---|
| Host | `DB_HOST` |
| Port | `DB_PORT` (az Aiven **nem** 3306-ot ad, hanem egy saját portot) |
| Database | `DB_DATABASE` → `defaultdb` |
| User | `DB_USERNAME` → `avnadmin` |
| Password | `DB_PASSWORD` |

### SSL CA cert — ez már megvan

Az Aiven **kötelezően TLS-t használ**, ezért kell a CA cert. Ez a lépés **már el
van végezve**: a tanúsítvány a repóban van `app/database/certs/ca.pem` néven, és
commitolva is van.

**Egyetlen `ca.pem` kell** — kliens-tanúsítvány és privát kulcs **nem**. Az Aiven
szerveroldali TLS-t használ, ez a fájl csak a *szerver* tanúsítványának
ellenőrzésére szolgál, nem mutual TLS-hez. A fájl nem titkos (publikus CA cert,
nem kulcs), ezért verziókövethető.

**Nem kell `MYSQL_ATTR_SSL_CA` env változót beállítani** sem a Vercelen, sem
GitHub secretként — a `config/database.php` magától feloldja. Az env változó csak
felülbírálásra szolgál, ha máshol tartod a fájlt.

> **Miért `database/certs/` és nem `storage/certs/`:** serverless futtatáskor az
> `api/index.php` a `storage_path()`-ot `/tmp`-re tereli, mert a filesystem
> read-only. Egy `storage/` alatti cert ezért futásidőben nem lenne megtalálható.
> A `database_path()` a telepítés gyökeréhez kötött, tehát stabil.
> Lefedve: `app/tests/Feature/MysqlSslCaTest.php`.

Ha egyszer új certet kapsz az Aiventől (10 évente, vagy service újraépítésnél),
csak írd felül ugyanezen a néven:

```bash
cp ~/Downloads/ca.pem app/database/certs/ca.pem
```

### Ellenőrzés

A GUI kliensedből csatlakozz a prod adatbázishoz. Vagy CLI-ből:

```bash
mysql -h <aiven-host> -P <aiven-port> -u avnadmin -p \
      --ssl-ca=app/database/certs/ca.pem defaultdb -e "select version();"
```

### Limitek, amiket tudni kell

| Limit | Érték | Jelentése nálad |
|---|---|---|
| Disk | 1 GB | bőven elég, az adat seedelt demo-tartalom |
| RAM | 1 GB | elég |
| `max_connections` | **76** | lásd alább |
| SLA | nincs | szakdolgozatnál vállalható |

**A connection limitről:** minden serverless function-példány saját DB-kapcsolatot
nyit, és MySQL-hez — ellentétben a Postgresszel, ahol van pgBouncer — nincs
serverless-barát connection pooler. Demo-forgalomnál a 76 bőven elég. Ha egyszer
terhelés alatt `Too many connections` hibát látsz, ez az ok, nem a kód.

Az Aiven fenntartja a jogot, hogy **hosszan használaton kívüli** free service-t
leállítson. A napi cron (9. lépés) minden nap futtat DB-műveletet, ami ezt
megelőzi.

---

## 3. Cloudflare R2

### 3.0 R2 aktiválása (egyszeri)

Mielőtt bucketet tudnál létrehozni, az R2-t aktiválni kell a fiókon:
Cloudflare dashboard → **R2** → *Get started with R2* →
**Add R2 subscription to my account**. `Total Due Now: $0.00`.

> **Bankkártya kell hozzá.** Az apróbetű szerint a számlázás a „payment method
> on file"-ra megy, tehát a Cloudflare kártyát kér a fiókhoz akkor is, ha a
> ingyenes kereten belül maradsz. Ez eltér az Aiventől, ahol nem kell kártya.
> A díj $0 marad, amíg a limitek alatt vagy.

Az ingyenes keret:

| | Keret | Mire kell |
|---|---|---|
| Storage | 10 GB / hó | pár kisállat-fotó, töredéke a keretnek |
| Class A (írás: PUT, LIST) | 1M művelet / hó | feltöltésenként 1–2 |
| Class B (olvasás: GET) | 10M művelet / hó | képmegjelenítés |

A **zero egress fee** itt lényeges: a képek kiszolgálása nem számláz forgalmi
díjat. A fenti árak a *Standard* storage class-ra vonatkoznak — alapból ez
használatos, nincs teendő.

### 3.1 Bucket

Cloudflare dashboard → **R2** → *Create bucket*: `tappmancs-uploads`.

### 3.2 Publikus hozzáférés — ez kell az `AWS_URL`-hez

A bucket alapból privát. A képek kiszolgálásához publikussá kell tenni, és erre
**két külön út** van a bucket *Settings* fülén:

| | Public Development URL | Custom Domain |
|---|---|---|
| Beállítás | egy kattintás | a domain Cloudflare zónába kell |
| Cím | `https://pub-<hash>.r2.dev` | `https://kepek.tappmancs-szakdolgozat.hu` |
| Rate limit | **van** | nincs |
| CDN cache | nincs | van |
| Mire szánták | fejlesztés | éles kiszolgálás |

**Most ezt válaszd:** *Settings* → **Public Development URL** → *Enable*.
Az így kapott `https://pub-<hash>.r2.dev` lesz az `AWS_URL`.

> **Tudnod kell:** a Cloudflare dokumentációja szerint az `r2.dev` végpont
> rate-limitelt, és „should only be used for development purposes". Nincs rajta
> cache, és terhelés alatt dobhat. Szakdolgozat-forgalomnál (néhány látogató,
> egy védés) ez a gyakorlatban elég, de ha éles kiszolgálást akarsz, lásd lent
> a custom domaint.

Enélkül a lépés nélkül a feltöltés **működni fog**, de a képek nem jelennek meg
— ez a leggyakoribb néma hiba ebben a szakaszban.

#### Opcionális: custom domain (éles kiszolgálás)

Ha a rate limit zavar, köss a buckethez saját aldomaint, pl.
`kepek.tappmancs-szakdolgozat.hu`. Ehhez a domainnek **Cloudflare zónának kell
lennie** (névszerver-váltás, vagy partial CNAME setup).

Ez a 7. lépést is érinti: ha a DNS a Cloudflare-hez kerül, az apex rekord
(Vercel, `76.76.21.21`) **DNS-only** legyen — szürke felhő, ne proxyzott —,
különben ütközhet a Vercel tanúsítvány-kezelésével. Az R2 aldomain viszont
proxyzott (narancs felhő).

Az `AWS_URL` ekkor az aldomain lesz, minden más változatlan.

### 3.3 API token

R2 → *Manage API Tokens* → *Create API token*:

- Permission: **Object Read & Write**
- Bucket: `tappmancs-uploads` (ne „All buckets")
- Megkapod: Access Key ID + Secret Access Key

A secretet **egyszer** látod — másold ki rögtön.

### 3.4 S3 végpont

```
https://<account-id>.r2.cloudflarestorage.com
```

Az account ID az R2 áttekintő oldalán van. A bucket nevét **ne** tedd bele — az
SDK fűzi hozzá.

### Miért `AWS_*` nevűek az env változók, ha nincs AWS?

Az R2 az **S3 protokollt** beszéli, ezért a Laravel beépített `s3` diskje és a
`league/flysystem-aws-s3-v3` csomag kezeli — az pedig `AWS_` prefixű változókat
olvas. **Nincs AWS-fiók a láncban**, a végpont a Cloudflare-é. Ezt a dolgozatban
is érdemes így megfogalmazni.

### Ellenőrzés

Tölts fel egy tesztfájlt a bucketbe a Cloudflare felületén, és nyisd meg a
publikus URL-jén böngészőből. Ha 404 vagy 403, a Public Development URL nincs
engedélyezve (3.2).

---

## 4. App key

```bash
cd app && php artisan key:generate --show
```

Másold ki. **Ugyanezt** az értéket kell beírni a Vercel envbe *és* a GitHub
secretbe — ha eltérnek, a GitHub Actions-ben futó artisan más kulccsal dolgozik,
mint az alkalmazás.

---

## 5. GitHub secrets

Repo → *Settings* → *Secrets and variables* → *Actions* → *New repository secret*.

Pontosan ez a **hat** secret kell (a `.github/workflows/deploy.yml` ezekre
hivatkozik):

| Secret | Érték |
|---|---|
| `APP_KEY` | a 4. lépésből |
| `DB_HOST` | Aiven host |
| `DB_PORT` | Aiven port |
| `DB_DATABASE` | `defaultdb` |
| `DB_USERNAME` | `avnadmin` |
| `DB_PASSWORD` | Aiven jelszó |

`MYSQL_ATTR_SSL_CA` **nem kell** — a cert a repóból oldódik fel (2. lépés).

**Töröld a régieket:** `EC2_SSH_KEY`, `EC2_HOST` — ezekre már semmi nem hivatkozik.

---

## 6. Vercel projekt

1. [vercel.com](https://vercel.com) → *Add New* → *Project* → importáld a
   GitHub repót.
2. **Root Directory: `app`** ← ezt ne hagyd ki. A repó monorepo (npm workspaces),
   a Laravel az `app/` alatt van. Ha ezt elfelejted, a build nem találja a
   `composer.json`-t.
3. Framework Preset: **Other**.
4. Build commandot **ne írj be** — a `app/vercel.json` adja
   (`npm install && npm run build`). A composer installt a `vercel-php` runtime
   végzi automatikusan.

### A runtime verzióról

A `app/vercel.json` `vercel-php@0.8.0`-t pinel, ami **PHP 8.4**. Ez tudatos
döntés: a legfrissebb `0.9.0` PHP 8.5-öt ad, ott viszont a
`PDO::MYSQL_ATTR_SSL_CA` konstans deprecated, és a működés a
`scripts/fix-php85-pdo.php` vendor-patchére hagyatkozna. Egy serverless buildben
olyan patchre épülni, ami ha nem fut le, *minden* kérést 500-ra visz, nem
vállalható — főleg most, hogy MySQL driverrel ez a kódút élesben is használatba
kerül.

### Env változók

*Settings* → *Environment Variables*, **Production** scope-ra:

```dotenv
APP_NAME=Tappmancs
APP_ENV=production
APP_KEY=                      # a 4. lépésből
APP_DEBUG=false
APP_URL=https://tappmancs-szakdolgozat.hu
APP_LOCALE=hu
APP_FALLBACK_LOCALE=hu

# Serverless: a filesystem read-only, ezért stderr-re logolunk.
# A logok a Vercel function logjában jelennek meg.
LOG_CHANNEL=stderr
LOG_LEVEL=warning

# Aiven MySQL. MYSQL_ATTR_SSL_CA nem kell: a config/database.php
# feloldja a database/certs/ca.pem fájlból (2. lépés).
DB_CONNECTION=mysql
DB_HOST=<aiven-host>
DB_PORT=<aiven-port>
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=<aiven-pass>

# Nem lehet "file" — nincs írható, kérések között megosztott filesystem
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync

# A domain csak HTTPS-en szolgál ki, ne bízzuk auto-detectre
SESSION_SECURE_COOKIE=true

# Feltöltések: Cloudflare R2 (S3-kompatibilis)
UPLOADS_DISK=s3
AWS_ACCESS_KEY_ID=<r2-access-key>
AWS_SECRET_ACCESS_KEY=<r2-secret>
AWS_DEFAULT_REGION=auto
AWS_BUCKET=tappmancs-uploads
AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
AWS_URL=<a 3.2 lépésben kapott pub-….r2.dev URL>
AWS_USE_PATH_STYLE_ENDPOINT=false

# A Vercel Cron ezt küldi Bearer tokenként
CRON_SECRET=<openssl rand -hex 32>
```

---

## 7. Domain: tappmancs-szakdolgozat.hu

A Vercel Hobby tieren a custom domain **ingyenes**, a TLS tanúsítványt
(Let's Encrypt) a Vercel automatikusan állítja ki és újítja.

1. Vercel → *Settings* → *Domains* → add `tappmancs-szakdolgozat.hu`.
2. Add hozzá a `www.tappmancs-szakdolgozat.hu`-t is, és állítsd
   **redirectnek az apexre**. Így egy kanonikus hoston fut minden, és a session
   cookie sem hasad szét két host között.
3. DNS a `.hu` regisztrátorod felületén:

| Típus | Név | Érték |
|---|---|---|
| A | `@` (apex) | `76.76.21.21` |
| CNAME | `www` | a Vercel által kiírt `cname.vercel-dns…` érték |

> **A konkrét értékeket a Vercel *Domains* füléről olvasd ki, ne ebből a
> táblázatból.** A Vercel dokumentációja is projektspecifikusnak jelzi őket, és a
> CNAME célpontja több változatban él (`cname.vercel-dns.com`,
> `cname.vercel-dns-0.com`). CLI-ből:
> `vercel domains inspect tappmancs-szakdolgozat.hu`

Apex domainre **nem lehet CNAME-et** tenni (DNS-standard), ezért ott A rekord kell.

### CAA rekord

Ha a domainen van CAA rekord, engedned kell a Let's Encryptet, különben a Vercel
nem tud tanúsítványt kiállítani:

```
0 issue "letsencrypt.org"
```

Ha egyáltalán nincs CAA rekordod, az bármely CA-t engedi — nincs mit tenni.

### HTTPS a proxy mögött

A Vercel terminálja a TLS-t, és a sémát csak az `X-Forwarded-Proto` headerben
adja tovább. Ezért a `bootstrap/app.php`-ban be van állítva a
`trustProxies(at: '*')`. Enélkül a Laravel HTTP-nek látná a kérést, és `http://`
URL-eket generálna egy HTTPS-es oldalon — blokkolt CSS/képek és lefokozott
login-redirect lenne a következmény.

A wildcard itt azért helyes, mert a Vercel proxy IP-i nem fix range-ben vannak,
és az origin **csak** a proxyn keresztül érhető el. Saját VM-en ezt nem így
kellene beállítani. Lefedve: `app/tests/Feature/ProxiedHttpsTest.php`.

### DNS propagáció

```bash
dig +short tappmancs-szakdolgozat.hu
dig +short www.tappmancs-szakdolgozat.hu
```

A `.hu` zónák terjedése akár órákig tarthat. A Vercel *Domains* fülén zöld
pipa jelzi, ha átállt és a cert kiállt.

---

## 8. Push

Most, és csak most:

```bash
git push origin main
```

Két dolog indul el egyszerre:

| Mi | Hol követed |
|---|---|
| Vercel build + deploy | Vercel dashboard → *Deployments* |
| `migrate --force` az Aiven ellen | GitHub → *Actions* → *Deploy* |

**Ellenőrzés:** mindkettő zöld. A migráció a 11 migrációt futtatja — ezek MySQL-en
lokálisan már tisztán lefutottak, úgyhogy itt nem számítok gondra.

---

## 9. Első seed

Egyszeri, **kézi** lépés. Szándékosan nem fut pushra, hogy egy véletlen push ne
írhassa újra az éles adatokat:

```
GitHub → Actions → Deploy → Run workflow → seed: true → Run workflow
```

Ez futtatja: `migrate --force`, `db:seed --force`, `demo:reset`.

**Ellenőrzés:** a Vercel által adott `*.vercel.app` címen már látszanak a
menhelyek és a kisállatok.

### Cron

A `app/vercel.json` két napi cront definiál (Hobby tieren ez a maximum, és csak
napi felbontás van):

| Útvonal | Parancs | Idő (UTC) |
|---|---|---|
| `/cron/prune-uploads` | `uploads:prune` | 03:00 |
| `/cron/demo-reset` | `demo:reset` | 04:00 |

A végpontok `CRON_SECRET` nélkül **404-et** adnak, hogy kívülről ne is legyenek
észlelhetők. Lefedve: `app/tests/Feature/Routes/CronRoutesTest.php`.

Kézi teszt:

```bash
curl -s -o /dev/null -w "%{http_code}\n" \
  https://tappmancs-szakdolgozat.hu/cron/prune-uploads
# -> 404 (nincs token)

curl -H "Authorization: Bearer <CRON_SECRET>" \
  https://tappmancs-szakdolgozat.hu/cron/prune-uploads
# -> {"task":"prune-uploads","output":"..."}
```

A napi `demo:reset` mellékhatása, hogy a demo-adatok maguktól visszaállnak —
bemutatáskor hasznos.

---

## 10. Ellenőrzés

### Automatikus

```bash
cd app-e2e
E2E_BASE_URL=https://tappmancs-szakdolgozat.hu npm run test:e2e:prod
```

A `test:e2e:prod` kihagyja a `@mutating` tageket, tehát nem módosít éles adatot.
Cold start miatt az első kérés lassabb — ha emiatt flaky, a Playwright timeoutot
emeld, **ne a tesztet lazítsd**.

### Kézi — ezt ne hagyd ki

| Mit | Miért |
|---|---|
| **Képfeltöltés** (új kisállat képpel) | Ez az egyetlen út, amit az e2e nem fed le, és pont ez ment át R2-re |
| Kép megjelenik a listában és az adatlapon | Az `AWS_URL` helyességét bizonyítja |
| Regisztráció → login → logout | Session a MySQL-ben, cookie HTTPS-en |
| CSS és képek betöltenek | A statikus asset routing működik |
| Menhely szerkesztése borítóképpel | A `Storage::move` út R2-n |

Böngésző devtools → *Console*: ne legyen mixed-content figyelmeztetés. Ha van,
az a `trustProxies` vagy az `APP_URL` jele.

---

## 11. Az AWS leállítása

**Csak akkor kezdj hozzá, ha a 10. lépés zöld** — addig az EC2 a visszaállási
lehetőséged.

Az alábbi sorrend szándékos: a számlázó tételek előre kerültek.

### 11.1 Elastic IP felszabadítása — ez számláz

EC2 konzol → *Network & Security* → **Elastic IPs**

1. Jelöld ki a címet → *Actions* → **Disassociate**
2. Majd *Actions* → **Release Elastic IP addresses**

> **Miért elöl van:** 2024. február 1. óta az AWS **minden publikus IPv4 címet
> számláz** — nem csak a nem használtakat, ahogy korábban. A díj
> **$0.005 / IP / óra**, ami **kb. $43.80 / év** egyetlen címre. Ez a
> leggyakrabban ottmaradó tétel egy „leállított" EC2 után: az instance nem fut,
> a cím mégis ketyeg.

### 11.2 Instance terminálása

EC2 → *Instances* → jelöld ki a `tappmancs` instance-t →
*Instance state* → **Terminate instance**

A „Stop" **nem elég**: megállított instance-nál a tároló (EBS) tovább számláz.

### 11.3 EBS volume-ok ellenőrzése

EC2 → *Elastic Block Store* → **Volumes**

Szűrj `State = available`-re. Ha a root volume-on nem volt bekapcsolva a
*Delete on termination*, a terminálás után itt marad, és **tovább számláz**.
Töröld: *Actions* → *Delete volume*.

### 11.4 Snapshotok és AMI-k

EC2 → *Elastic Block Store* → **Snapshots** — töröld a sajátjaidat.

Ha készítettél AMI-t: *Images* → **AMIs** → *Deregister*, **majd** töröld a
hozzá tartozó snapshotot is (a deregister önmagában nem törli a tárolót).

### 11.5 Security group és key pair

- *Network & Security* → **Security Groups** → töröld a `tappmancs`-hoz
  létrehozott csoportot. A `default` nem törölhető, az nem is számláz.
- *Network & Security* → **Key Pairs** → töröld a deployhoz használt kulcsot.
  A privát párját a GitHub secretből is töröltük (5. lépés).

### 11.6 VPC — itt lehet drága tétel

VPC konzol → ha saját VPC-t hoztál létre:

- **NAT Gateway** — ha van, ez a *legdrágább* AWS-tétel, ami így ottmaradhat
  (órában + adatforgalomban számláz). Töröld.
- **Elastic IP** a NAT-hoz — szabadítsd fel (lásd 11.1).

Ha a default VPC-t használtad, itt nincs dolgod.

### 11.7 CloudWatch

CloudWatch → *Logs* → **Log groups** → töröld a `tappmancs`-hoz tartozókat.
*Alarms* → töröld a riasztásokat. Jellemzően free tier alatt vannak, de a
retenció nélkül hagyott log group hosszú távon számláz.

### 11.8 Route 53

Route 53 → **Hosted zones**

Ha csináltál hosted zone-t, az **$0.50/hó/zóna** (az első 25 zónáig) még akkor
is, ha egyetlen rekord sincs benne. A `.hu` domaint nem az AWS kezeli, szóval
valószínűleg nincs ilyened — de nézd meg, mert ez a fajta tétel évekig elfut
észrevétlenül.

### 11.9 IAM

IAM → *Users* → ha csináltál deployhoz access key-t, töröld a kulcsot és a
usert. (A projekt `.env`-jében az `AWS_ACCESS_KEY_ID` üres volt, tehát S3-hoz
jó eséllyel nem jött létre semmi.)

### 11.10 Számla ellenőrzése

Billing konzol → **Bills** → a *következő* hónap elején ellenőrizd, hogy $0.

Hasznos biztosíték: Billing → *Budgets* → **Create budget** → *Zero spend
budget*. Ez emailt küld, ha bármi elkezd számlázni.

> Az aktuális hónapban még látni fogsz tételeket a leállítás előtti időszakra —
> ez normális. A következő teljes ciklus a mérvadó.

### 11.11 Teljes fiókzárás (opcionális)

Ha semmi mást nem használsz az AWS-en: Account → *Close Account*.

A fiók 90 napig visszanyitható, utána véglegesen törlődik. Csak akkor tedd, ha
biztos vagy benne, hogy nincs ott más projekt.

### Leállítási checklist

```
[ ] 11.0  Adatmentés, ha volt éles tartalom (0. lépés)
[ ] 11.1  Elastic IP: disassociate + release
[ ] 11.2  EC2 instance: TERMINATE (nem stop)
[ ] 11.3  EBS volume-ok: available státuszúak törlése
[ ] 11.4  Snapshotok és AMI-k törlése
[ ] 11.5  Security group + key pair törlése
[ ] 11.6  VPC: NAT Gateway (ha van!) + saját VPC
[ ] 11.7  CloudWatch log groupok és alarmok
[ ] 11.8  Route 53 hosted zone (ha van)
[ ] 11.9  IAM access key és user
[ ] 11.10 Zero-spend budget beállítása, számla ellenőrzése a köv. hónapban
[ ] 11.11 (opcionális) fiók zárása
```

---

## Hibakeresés

A Vercel function logja: Vercel dashboard → *Deployments* → a deploy →
*Functions* → *Logs*. Mivel `LOG_CHANNEL=stderr`, a Laravel logok is itt vannak.

### A CSS és a képek 404-eznek, az oldal csupasz

**Ez a legvalószínűbb hiba, és ezt nem tudtam előre verifikálni.**

A statikus asset routing a `app/vercel.json` `routes` tömbjében van:

```json
{ "src": "/build/(.*)", "dest": "/public/build/$1" },
{ "src": "/images/(.*)", "dest": "/public/images/$1" }
```

A runtime repójában nincs Laravel példa, így ez a legjobb ismert minta alapján
készült. Ha nem működik, két dolgot próbálj:

1. Add a `routes` tömb **elejére**: `{ "handle": "filesystem" }`
2. Vagy állítsd be a `vercel.json`-ban: `"outputDirectory": "public"`

A javítás helye a `vercel.json`, **nem** az alkalmazás kódja.

### Minden kérés 500

Nézd a function logot. Tipikus okok:

| Log | Ok |
|---|---|
| `SQLSTATE[HY000] [2002]` | `DB_HOST`/`DB_PORT` téves, vagy az Aiven még `REBUILDING` |
| SSL/certificate hiba | a `database/certs/ca.pem` nincs a repóban, vagy a `.vercelignore` kizárja |
| `No application encryption key` | `APP_KEY` nincs beállítva a Vercel envben |
| `Permission denied` / `mkdir` | a storage path nem `/tmp`-re megy; ezt az `api/index.php` kezeli |
| `Table ... doesn't exist` | a migráció nem futott le — nézd a GitHub Actions-t |

### A képek nem jelennek meg (de a feltöltés sikeres)

Az `AWS_URL` téves, vagy a bucketen nincs engedélyezve a **Public Development
URL** (3.2). Nyisd meg egy feltöltött fájl URL-jét közvetlenül — ha 403, ez a hiba.

### `Too many connections`

Az Aiven free `max_connections=76`. Serverless alatt ez elérhető terhelés
mellett. Szakdolgozat-forgalomnál nem fordul elő; ha mégis, a szolgáltató-váltás
a megoldás (lásd lent), nem a kód.

### Login után visszadob, vagy mixed-content figyelmeztetés

A `trustProxies` nem érvényesül. Ellenőrizd, hogy a `bootstrap/app.php`-ban
benne van-e, és hogy az `APP_URL` `https://`-sel kezdődik.

### A deploy lefut, de 404 mindenre

A Vercel *Root Directory* nincs `app`-ra állítva (6. lépés, 2. pont).

---

## Visszaállás (rollback)

A 11. lépés előtt az EC2 még fut és működik, tehát:

| Mit | Hogyan |
|---|---|
| Vercel deploy visszavonása | Vercel → *Deployments* → korábbi deploy → *Promote to Production* |
| Domain visszaállítása | DNS-ben vissza az EC2 IP-jére |
| Kód visszaállítása | lásd lent |

A kód visszaállítása a migráció előtti állapotra — a `pre-vercel` tagtől
(0. lépés), hogy ne kelljen commit-hasheket felsorolni:

```bash
git revert --no-commit pre-vercel..HEAD
git commit -m "Revert Vercel migration"
```

Ez minden migrációs commitot visszafordít, függetlenül attól, hány darab lett.

Ezért is van a 11. lépés a *végén*, és nem a push előtt.

---

## Szolgáltató-váltás később

Ha az Aiven 1 GB-ja vagy a 76 kapcsolat szűk lesz, a váltás **5 env változó**
(`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) plusz az új
szolgáltató CA certje a `database/certs/ca.pem` helyére. **Kódváltozás nincs.**

| Szolgáltató | Ár | Mit ad |
|---|---|---|
| Aiven (jelenlegi) | $0 | 1 GB disk, 76 kapcsolat |
| Railway | $5/hó | 0.5 GB RAM, 1 GB tároló, egyklikkes MySQL, jó UI |
| DigitalOcean | $15.15/hó | 1 GB RAM, 10 GB tároló |

A PlanetScale 2024 áprilisában megszüntette az ingyenes tierjét, MySQL-je
$39/hó-tól indul — ezért nem szerepel.
