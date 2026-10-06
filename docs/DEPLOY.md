# Migráció: AWS EC2 → DigitalOcean Droplet

Teljes, lépésenkénti útmutató a nulláról az élő oldalig, majd az AWS leállításáig.

## Hol tartasz

```
KÉSZ (commitolva, de NINCS pusholva)
  [x] SQLite -> MySQL (290 teszt verifikálva MySQL-en)
  [x] Feltöltés UploadDisk seam mögött (env-vezérelt disk)
  [x] Session és cache database driverre
  [x] deploy.yml: EC2 helyett DigitalOcean, asset build a runneren

RÁD VÁR
  [ ] 1.  Droplet létrehozása            -> 1. lépés
  [ ] 2.  Szerver alapbeállítás          -> 2. lépés
  [ ] 3.  nginx + PHP + MySQL telepítés  -> 3. lépés
  [ ] 4.  Adatbázis létrehozása          -> 4. lépés
  [ ] 5.  Alkalmazás kihelyezése         -> 5. lépés
  [ ] 6.  Domain + TLS                   -> 6. lépés
  [ ] 7.  Cron (scheduler)               -> 7. lépés
  [ ] 8.  GitHub Actions deploy          -> 8. lépés
  [ ] 9.  Mentés                         -> 9. lépés
  [ ] 10. Ellenőrzés                     -> 10. lépés
  [ ] 11. AWS leállítása                 -> 11. lépés
```

### Tartalom

- [Mi változik és miért](#mi-változik-és-miért)
- [0. Előkészítés](#0-előkészítés)
- [1. Droplet](#1-droplet)
- [2. Szerver alapbeállítás](#2-szerver-alapbeállítás)
- [3. nginx, PHP, MySQL](#3-nginx-php-mysql)
- [4. Adatbázis](#4-adatbázis)
- [5. Alkalmazás kihelyezése](#5-alkalmazás-kihelyezése)
- [6. Domain és TLS](#6-domain-és-tls)
- [7. Cron](#7-cron)
- [8. GitHub Actions deploy](#8-github-actions-deploy)
- [9. Mentés](#9-mentés)
- [10. Ellenőrzés](#10-ellenőrzés)
- [11. AWS leállítása](#11-az-aws-leállítása)
- [Hibakeresés](#hibakeresés)
- [Visszaállás](#visszaállás-rollback)

---

## Mi változik és miért

### Előtte (AWS)

```
GitHub push -> Actions -> SSH -> EC2 instance
                                  ├─ nginx + PHP-FPM
                                  ├─ SQLite fájl
                                  └─ képek: storage/app/public
```

### Utána (DigitalOcean)

```
GitHub push -> Actions ─┬─> asset build a runneren
                        └─> SSH -> Droplet ($6/hó, fix)
                                    ├─ nginx + PHP-FPM 8.4
                                    ├─ MySQL          <- nincs külön DB-szolgáltató
                                    ├─ képek a lemezen <- nincs object storage
                                    ├─ cron            <- igazi scheduler
                                    └─ certbot         <- ingyenes TLS

Szolgáltatók száma: 1
```

A szerkezet szándékosan hasonlít a régire. Az érdemi különbségek: **SQLite
helyett MySQL**, a költség **fix és alacsony**, az asset build a **runneren**
fut, és nincs az AWS publikus IPv4 díja.

### Mit nem viszünk tovább

A projekt megjárt egy Vercel-irányt is, ami serverless modellt jelentett volna.
Az ott szükséges megoldások itt mind feleslegesek, és ki is kerültek:

| Serverless kényszer | Miért nem kell itt |
|---|---|
| `/tmp`-re terelt storage path | a lemez írható |
| Object storage (R2/B2) a képeknek | a képek a saját lemezen vannak |
| Cron HTTP-végpontok | van rendes crontab |
| `trustProxies(at: '*')` | lásd lent |

A `trustProxies` wildcardot **kifejezetten el kellett távolítani**. Serverless
platformon helyes volt, mert ott az origin csak a proxyn át érhető el. Saját
gépen az origin közvetlenül is elérhető, tehát bárki küldhet
`X-Forwarded-Proto` vagy `X-Forwarded-Host` fejlécet, és a Laravel elhinné —
ebből hamis URL-generálás és cache-mérgezés lehet. Az nginx a valódi sémát a
`HTTPS` fastcgi paraméterrel adja át, proxy-bizalom nélkül.
Lefedve: `app/tests/Feature/ForwardedHeadersTest.php`.

### Mi marad a Vercel-kitérőből

- **MySQL** — 290/290 teszt verifikálva rajta
- **`App\Support\UploadDisk`** — a feltöltés diskje egy helyen dől el, env-ből.
  Itt `public` értéken marad, de ha egyszer object storage kellene, egy env
  változó

---

## 0. Előkészítés

```bash
cd ~/Projects/tappmancs/tappmancs_szakdolgozat_24-25_26
git log --oneline -5
```

### Pushold fel a kódot

Az 5. lépés a szerverre klónozza a repót, tehát a migráció kódjának **kint kell
lennie a GitHubon**, mielőtt odáig érnél:

```bash
git push origin main
```

Ez elindítja a deploy workflow-t, ami **el fog hasalni**, mert a `DEPLOY_*`
secretek még nem léteznek (8. lépés). Ez várt és ártalmatlan — egy sikertelen
futás az Actions fülön. A szerver kézi beállítása (5–7. lépés) ettől
függetlenül megy, és a 8. lépés után a következő push már végig fog futni.

### Jelöld meg a visszaállási pontot

```bash
git tag pre-migration 5d36b46
git push origin pre-migration
```

### Van-e éles adat az EC2-n?

A terv friss seed. Ha van olyan tartalom, amit nem akarsz elveszíteni, mentsd le
**most**:

```bash
ssh ubuntu@<EC2_HOST>
cd /var/www/tappmancs/app
cp database/database.sqlite ~/backup.sqlite
tar czf ~/uploads.tar.gz storage/app/public
exit
scp ubuntu@<EC2_HOST>:~/backup.sqlite ./
scp ubuntu@<EC2_HOST>:~/uploads.tar.gz ./
```

---

## 1. Droplet

DigitalOcean → *Create* → **Droplets**

| Beállítás | Érték |
|---|---|
| Region | **Frankfurt** (legközelebbi) |
| Image | **Ubuntu 24.04 LTS** |
| Type | Basic → Regular → **$6/hó** (1 GB RAM, 25 GB SSD) |
| Authentication | **SSH key** (ne jelszó) |
| Hostname | `tappmancs` |

> **Miért nem a $4-es:** 0.5 GB RAM-on a MySQL (~400 MB) mellett alig marad
> valami a PHP-FPM-nek. Az 1 GB elég ehhez a projekthez.

### SSH kulcs

> **A beállítás során két külön kulcs szerepel — ne keverd őket:**
>
> | | Hol generálod | Mi megy hova |
> |---|---|---|
> | **Ez a lépés** — te lépsz be | a saját gépeden | a **publikus** fele a DigitalOceanhoz |
> | **8. lépés** — a CI lép be | **a dropleten** | a **privát** fele GitHub secretbe |
>
> A saját privát kulcsod soha ne kerüljön GitHub secretbe: az a személyes
> azonosítód minden szerverhez. A deploy kulcs egy gépé, és önállóan
> visszavonható.

Ha már használsz SSH-t (pl. GitHubhoz), jó eséllyel van kulcsod:

```bash
ls ~/.ssh/*.pub
```

Ha van, használd azt — nem kell újat csinálni. Másold a vágólapra:

```bash
pbcopy < ~/.ssh/id_ed25519.pub
```

Ha nincs, generálj egyet:

```bash
ssh-keygen -t ed25519 -C "$(whoami)@$(hostname)"
# Enter a fájlnévnél (alapértelmezett hely), majd adj meg egy jelmondatot
pbcopy < ~/.ssh/id_ed25519.pub
```

A jelmondat (passphrase) nem kötelező, de ajánlott: ha ellopják a laptopot, a
kulcs önmagában használhatatlan. macOS-en egyszer kell beírni, utána a
kulcskarika megjegyzi:

```bash
ssh-add --apple-use-keychain ~/.ssh/id_ed25519
```

A DigitalOcean *Create Droplet* oldalán az **SSH Key** mezőbe illeszd be
(`Cmd+V`) a publikus kulcsot.

**Ellenőrzés:** `ssh root@<droplet-ip>` bejelentkezik.

---

## 2. Szerver alapbeállítás

```bash
ssh root@<droplet-ip>
```

### Deploy felhasználó

Root helyett dedikált felhasználó:

```bash
adduser --disabled-password --gecos "" deploy
usermod -aG sudo deploy
rsync --archive --chown=deploy:deploy ~/.ssh /home/deploy
```

A deploy workflow-nak `sudo` kell a PHP-FPM újratöltéséhez, jelszó nélkül:

```bash
echo 'deploy ALL=(ALL) NOPASSWD: /bin/systemctl reload php8.4-fpm' \
  > /etc/sudoers.d/deploy-fpm
chmod 440 /etc/sudoers.d/deploy-fpm
```

### Tűzfal

```bash
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable
ufw status
```

> Portszám és nem `'Nginx Full'` profil: azt a profilt az nginx csomag hozza
> magával, ami még nincs telepítve (3. lépés), így a profilnév ezen a ponton
> `ERROR: Could not find a profile matching 'Nginx Full'` hibát adna.

**A MySQL portja (3306) szándékosan nincs nyitva** — kívülről nem lesz elérhető.
A hozzáférés SSH-alagúton megy, lásd 4. lépés.

### Swap

1 GB RAM mellett a composer és a MySQL együtt elfogyhat. A swap olcsó biztosíték:

```bash
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile && swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab
free -h
```

---

## 3. nginx, PHP, MySQL

```bash
apt update && apt upgrade -y

# PHP 8.4 (az Ubuntu alap repójában régebbi van)
apt install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update

apt install -y nginx mysql-server \
  php8.4-fpm php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl \
  php8.4-zip php8.4-gd php8.4-bcmath php8.4-intl \
  git unzip curl

# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

> **Miért PHP 8.4 és nem 8.5:** a `scripts/fix-php85-pdo.php` arra utal, hogy a
> 8.5 vendor-patchelést igényelne a MySQL SSL konstans miatt. 8.4-en ez a
> probléma fel sem merül, és a Laravel 11 ott rendesen támogatott.

**Ellenőrzés:**

```bash
php -v                 # 8.4.x
nginx -v
systemctl status mysql
```

---

## 4. Adatbázis

### MySQL biztonságos alapállapot

```bash
mysql_secure_installation
```

A jelszó-ellenőrzés szintjénél **1 (MEDIUM)** jó választás. Ez a később
létrehozott felhasználókra is érvényes: min. 8 karakter, szám, kis- és
nagybetű, és speciális karakter. Ha a jelszó nem felel meg, a `CREATE USER`
`ERROR 1819`-cel elszáll.

A többi kérdésre mind **y**: anonymous user törlése, remote root tiltása, test
adatbázis törlése, privilégiumok újratöltése.

> **A root jelszaváról:** Ubuntu 24.04-en a MySQL root felhasználója
> `auth_socket`-tel azonosít, ezért a `mysql_secure_installation` kihagyja a
> jelszó beállítását. Ez így helyes és biztonságosabb: a root csak a gépről,
> rendszer-rootként tud belépni. Emiatt a lenti parancs **`-p` nélkül** van.

### Adatbázis és felhasználó

```bash
mysql -u root
```

```sql
CREATE DATABASE tappmancs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tappmancs'@'localhost' IDENTIFIED BY '<erős-jelszó>';   -- MEDIUM szabály!
GRANT ALL PRIVILEGES ON tappmancs.* TO 'tappmancs'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

A `'localhost'` itt lényeges: a felhasználó **csak a gépről** tud belépni.

### Hozzáférés a saját gépedről — SSH alagúton

A MySQL nincs kint az interneten, de a GUI kliensed simán eléri SSH-n át.
Minden elterjedt kliens tudja natívan:

| Kliens | Hol |
|---|---|
| **TablePlus** | új kapcsolat → *Over SSH* fül |
| **MySQL Workbench** | Connection Method → *Standard TCP/IP over SSH* |
| **DBeaver** | kapcsolat → *SSH* fül |

| Mező | Érték |
|---|---|
| SSH Host | a droplet IP-je |
| SSH User | `deploy` |
| SSH Key | `~/.ssh/id_ed25519` |
| MySQL Host | `127.0.0.1` |
| MySQL Port | `3306` |
| MySQL User | `tappmancs` |
| Database | `tappmancs` |

Így ugyanabból a kliensből kezeled a lokális és az éles adatbázist, és
a 3306-os port kívülről zárva marad.

Parancssorból, ha inkább úgy:

```bash
ssh -L 3307:127.0.0.1:3306 deploy@<droplet-ip>
# másik terminálban:
mysql -h 127.0.0.1 -P 3307 -u tappmancs -p tappmancs
```

---

## 5. Alkalmazás kihelyezése

### Kód

```bash
su - deploy
sudo mkdir -p /var/www/tappmancs
sudo chown deploy:deploy /var/www/tappmancs
git clone https://github.com/DobrosiGergo/tappmancs_szakdolgozat_24-25_26.git /var/www/tappmancs
cd /var/www/tappmancs/app
composer install --no-dev --optimize-autoloader
```

### Env

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

```dotenv
APP_NAME=Tappmancs
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tappmancs-szakdolgozat.hu
APP_LOCALE=hu
APP_FALLBACK_LOCALE=hu

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tappmancs
DB_USERNAME=tappmancs
DB_PASSWORD=<a 4. lépésben megadott jelszó>

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
SESSION_SECURE_COOKIE=true

# A képek a gép lemezén vannak, nem kell object storage
FILESYSTEM_DISK=local
UPLOADS_DISK=public
```

### Migráció, seed, jogosultságok

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
sudo chown -R deploy:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

> A `storage:link` hozza létre a `public/storage` symlinket, amin keresztül a
> feltöltött képek kiszolgálódnak. Enélkül a feltöltés működik, de a képek
> 404-eznek.

### Asset build

Élesben az assetet a GitHub Actions buildeli (8. lépés). Az első kihelyezésnél
egyszer kézzel:

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
npm ci && npm run build
```

### nginx

```bash
sudo nano /etc/nginx/sites-available/tappmancs
```

```nginx
server {
    listen 80;
    server_name tappmancs-szakdolgozat.hu www.tappmancs-szakdolgozat.hu;
    root /var/www/tappmancs/app/public;

    index index.php;
    charset utf-8;
    client_max_body_size 12M;          # a képfeltöltés limitje

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    error_page 404 /index.php;
}
```

```bash
sudo ln -s /etc/nginx/sites-available/tappmancs /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

A `client_max_body_size 12M` a Livewire feltöltési limitjéhez igazodik
(`config/livewire.php`: 12 MB). Ha ezt kisebbre hagyod, az nginx dobja el a
feltöltést, mielőtt a PHP látná.

A PHP oldalon is emeld meg:

```bash
sudo sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 12M/' /etc/php/8.4/fpm/php.ini
sudo sed -i 's/^post_max_size = .*/post_max_size = 16M/' /etc/php/8.4/fpm/php.ini
sudo systemctl reload php8.4-fpm
```

**Ellenőrzés:** `curl -I http://<droplet-ip>` → `200`.

---

## 6. Domain és TLS

### DNS

A `.hu` regisztrátorod felületén:

| Típus | Név | Érték |
|---|---|---|
| A | `@` | a droplet IP-je |
| A | `www` | a droplet IP-je |

```bash
dig +short tappmancs-szakdolgozat.hu
```

Várd meg, amíg a droplet IP-jét adja vissza — a `.hu` zónák terjedése órákig
tarthat. **TLS-t csak utána kérj**, különben a Let's Encrypt validáció elbukik.

### Tanúsítvány

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d tappmancs-szakdolgozat.hu -d www.tappmancs-szakdolgozat.hu
```

A certbot átírja az nginx configot, és beállítja a HTTP → HTTPS átirányítást.
A megújítás automatikus (systemd timer):

```bash
sudo systemctl status certbot.timer
sudo certbot renew --dry-run
```

---

## 7. Cron

A `app/routes/console.php` két ütemezett parancsot tartalmaz:

| Parancs | Idő | Mit csinál |
|---|---|---|
| `uploads:prune` | 00:00 | félbehagyott feltöltések takarítása |
| `demo:reset` | 04:00 | demo-adatok visszaállítása |

Ehhez a Laravel schedulerét kell percenként futtatni:

```bash
sudo crontab -u deploy -e
```

```cron
* * * * * cd /var/www/tappmancs/app && php artisan schedule:run >> /dev/null 2>&1
```

Ez az egyetlen cron sor elég — a Laravel ütemezője innen kezeli mindkét
parancsot.

**Ellenőrzés:**

```bash
cd /var/www/tappmancs/app && php artisan schedule:list
```

---

## 8. GitHub Actions deploy

### Deploy kulcs

Ez **nem** az a kulcs, amivel te lépsz be (1. lépés). Ezt a dropleten
generálod, a GitHub Actions számára, és jelmondat nélkül — a CI-nak nincs
hova beírnia.

A dropleten, `deploy` felhasználóként:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/github_deploy -N ""
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys

cat ~/.ssh/github_deploy          # a PRIVÁT kulcs -> GitHub secret
```

A `cat` kimenetét **teljes egészében** másold, a
`-----BEGIN OPENSSH PRIVATE KEY-----` és `-----END OPENSSH PRIVATE KEY-----`
sorokkal együtt.

### Secretek

Repo → *Settings* → *Secrets and variables* → *Actions*:

| Secret | Érték |
|---|---|
| `DEPLOY_HOST` | a droplet IP-je |
| `DEPLOY_USER` | `deploy` |
| `DEPLOY_SSH_KEY` | a `~/.ssh/github_deploy` **privát** kulcs teljes tartalma |

**Töröld a régieket:** `EC2_SSH_KEY`, `EC2_HOST`.

### Mit csinál a workflow

1. A **runneren** buildeli az asseteket (`npm ci && npm run build`)
2. SSH-n behúzza a kódot a dropletre, és futtatja a `composer install`-t
3. Az assetet rsync-kel másolja fel
4. `migrate --force`, cache-ek újraépítése, PHP-FPM reload

> Az asset build azért van a runneren, mert a Vite build 1 GB RAM-on OOM-ra
> futhat, és egy megszakadt build a deploy közepén hagyja a rendszert.

A seedelés **kézi**, hogy egy push ne írhassa újra az éles adatokat:

```
Actions → Deploy → Run workflow → seed: true
```

---

## 9. Mentés

Menedzselt adatbázisnál ez a szolgáltató dolga volt. Itt a tiéd.

### Napi adatbázis-mentés

```bash
sudo mkdir -p /var/backups/tappmancs && sudo chown deploy:deploy /var/backups/tappmancs
nano ~/backup.sh
```

```bash
#!/bin/bash
set -e
STAMP=$(date +%F)
mysqldump -u tappmancs -p"$DB_PASSWORD" tappmancs | gzip > /var/backups/tappmancs/db-$STAMP.sql.gz
tar czf /var/backups/tappmancs/uploads-$STAMP.tar.gz -C /var/www/tappmancs/app storage/app/public
find /var/backups/tappmancs -type f -mtime +14 -delete
```

```bash
chmod +x ~/backup.sh
crontab -e
```

```cron
30 3 * * * DB_PASSWORD='<jelszó>' /home/deploy/backup.sh
```

### Droplet snapshot

DigitalOcean → Droplet → *Backups* — heti automatikus mentés, a droplet árának
20%-a (~$1.2/hó). Teljes gépet állít vissza, nem csak az adatot. Szakdolgozatnál
megéri.

> A mentést **töltsd is le időnként** a saját gépedre. Egy mentés, ami ugyanazon
> a gépen van, mint az adat, nem mentés.

---

## 10. Ellenőrzés

### Automatikus

```bash
cd app-e2e
E2E_BASE_URL=https://tappmancs-szakdolgozat.hu npm run test:e2e:prod
```

A `test:e2e:prod` kihagyja a `@mutating` tageket, tehát nem módosít éles adatot.

### Kézi

| Mit | Miért |
|---|---|
| **Képfeltöltés** (új kisállat képpel) | ezt az e2e nem fedi le |
| A kép megjelenik a listában | a `storage:link` symlink működik |
| Regisztráció → login → logout | session a MySQL-ben |
| HTTPS zöld lakat, nincs mixed content | a certbot és az `APP_URL` rendben |

---

## 11. Az AWS leállítása

**Csak akkor kezdj hozzá, ha a 10. lépés zöld** — addig az EC2 a visszaállási
lehetőséged. A sorrend szándékos: a számlázó tételek előre kerültek.

### 11.1 Elastic IP felszabadítása — ez számláz

EC2 → *Network & Security* → **Elastic IPs** → *Disassociate*, majd
**Release Elastic IP addresses**.

> 2024. február 1. óta az AWS **minden publikus IPv4 címet** számláz, nem csak a
> nem használtakat. A díj **$0.005 / IP / óra**, azaz **kb. $43.80 / év**
> egyetlen címre. Ez a leggyakrabban ottmaradó tétel egy „leállított" EC2 után:
> az instance nem fut, a cím mégis ketyeg.

### 11.2 Instance terminálása

EC2 → *Instances* → *Instance state* → **Terminate instance**.
A „Stop" **nem elég**: megállított instance-nál az EBS tovább számláz.

### 11.3 EBS volume-ok

EC2 → **Volumes** → szűrj `State = available`-re. Ha a root volume-on nem volt
*Delete on termination*, itt marad és számláz. Töröld.

### 11.4 Snapshotok és AMI-k

**Snapshots** → töröld a sajátjaidat. AMI esetén előbb *Deregister*, **majd** a
hozzá tartozó snapshot törlése — a deregister önmagában nem törli a tárolót.

### 11.5 Security group és key pair

Töröld a `tappmancs`-hoz tartozó security groupot és a deploy key pairt.
A `default` security group nem törölhető, az nem is számláz.

### 11.6 VPC — itt lehet drága tétel

Ha saját VPC-t hoztál létre: a **NAT Gateway** a legdrágább ottmaradó tétel
(óradíj + forgalom). Töröld, és szabadítsd fel a hozzá tartozó Elastic IP-t.
Default VPC esetén nincs teendő.

### 11.7 CloudWatch

**Log groups** és **Alarms** törlése. A retenció nélkül hagyott log group
hosszú távon számláz.

### 11.8 Route 53

**Hosted zones** — ha van, az **$0.50/hó/zóna** (az első 25 zónáig) üresen is.
A `.hu` domaint nem az AWS kezeli, szóval valószínűleg nincs — de nézd meg.

### 11.9 IAM

Töröld a deployhoz készült access key-t és usert.

### 11.10 Számla

Billing → **Bills**: a *következő* hónap elején ellenőrizd, hogy $0.
Biztosíték: Billing → *Budgets* → **Zero spend budget** — emailt küld, ha bármi
elkezd számlázni.

### 11.11 Fiókzárás (opcionális)

Account → *Close Account*. 90 napig visszanyitható. Csak akkor, ha nincs ott más
projekted.

### Checklist

```
[ ] 11.0  Adatmentés, ha volt éles tartalom (0. lépés)
[ ] 11.1  Elastic IP: disassociate + release
[ ] 11.2  EC2 instance: TERMINATE (nem stop)
[ ] 11.3  EBS volume-ok: available státuszúak törlése
[ ] 11.4  Snapshotok és AMI-k
[ ] 11.5  Security group + key pair
[ ] 11.6  VPC: NAT Gateway (ha van!)
[ ] 11.7  CloudWatch log groupok és alarmok
[ ] 11.8  Route 53 hosted zone (ha van)
[ ] 11.9  IAM access key és user
[ ] 11.10 Zero-spend budget, számla ellenőrzése a köv. hónapban
[ ] 11.11 (opcionális) fiók zárása
```

---

## Hibakeresés

Logok:

```bash
tail -f /var/www/tappmancs/app/storage/logs/laravel.log
sudo tail -f /var/log/nginx/error.log
sudo journalctl -u php8.4-fpm -f
```

### 500-as hiba minden oldalon

| Ok | Megoldás |
|---|---|
| `storage` nem írható | `sudo chown -R deploy:www-data storage bootstrap/cache && sudo chmod -R 775 storage bootstrap/cache` |
| Hiányzó `APP_KEY` | `php artisan key:generate` |
| DB kapcsolat | ellenőrizd a `.env` `DB_*` értékeit, és hogy a MySQL fut-e |
| Cache-elt régi config | `php artisan optimize:clear` |

### A képek 404-eznek

A `public/storage` symlink hiányzik:

```bash
cd /var/www/tappmancs/app && php artisan storage:link
```

### A CSS nem töltődik be

Az asset build nem futott le, vagy nem került fel. Nézd meg, hogy létezik-e a
`app/public/build/manifest.json`, és hogy a GitHub Actions `build` jobja zöld-e.

### A képfeltöltés nagy fájlnál elhasal

Az nginx `client_max_body_size` vagy a PHP `upload_max_filesize` kisebb, mint a
Livewire limitje (12 MB). Lásd 5. lépés.

### Kevés a memória

```bash
free -h
```

Ha a swap is tele van, lépj feljebb a $12-es dropletre (2 GB RAM). A DigitalOcean
felületén a *Resize* ehhez elég, újratelepítés nem kell.

### `Permission denied` a deploy workflow-ban

A `deploy` felhasználó nem tud `sudo systemctl reload php8.4-fpm`-et futtatni.
Lásd a 2. lépés sudoers beállítását.

---

## Visszaállás (rollback)

A 11. lépés előtt az EC2 még fut, tehát:

| Mit | Hogyan |
|---|---|
| Kód a dropleten | `cd /var/www/tappmancs && git reset --hard <előző-commit>` |
| Domain | DNS-ben vissza az EC2 IP-jére |
| Kód a repóban | lásd lent |
| Teljes gép | DigitalOcean → Droplet → *Backups* → restore |

A migráció előtti állapot a `pre-migration` tagnél (0. lépés):

```bash
git revert --no-commit pre-migration..HEAD
git commit -m "Revert DigitalOcean migration"
```

Ezért is van a 11. lépés a *végén*, és nem a deploy előtt.
