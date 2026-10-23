# Tappmancs: szakdolgozat (2024-2026)

Webalkalmazás állatmenhelyek és örökbefogadható kisállatok kezelésére.
Éles oldal: **https://tappmancs-szakdolgozat.hu**

A projekt 2024 decemberében indult, a leírás a 2026. októberi állapotot rögzíti.

## Tartalom

1. [A projekt célja](#1-a-projekt-célja)
2. [Funkciók és szerepkörök](#2-funkciók-és-szerepkörök)
3. [Technológiák](#3-technológiák)
4. [AI-val gyorsított fejlesztés](#4-ai-val-gyorsított-fejlesztés)
5. [Tervezés és megvalósítás](#5-tervezés-és-megvalósítás)
6. [Tesztelés](#6-tesztelés)
7. [Használható felhasználók](#7-használható-felhasználók)
8. [A repó felépítése](#8-a-repó-felépítése)
9. [Az éles szerver](#9-az-éles-szerver)
10. [Az éles adatbázis elérése](#10-az-éles-adatbázis-elérése)
11. [Deploy](#11-deploy)
12. [A projekt időrendje](#12-a-projekt-időrendje)
13. [Továbbfejlesztési lehetőségek](#13-továbbfejlesztési-lehetőségek)

---

## 1. A projekt célja

A Tappmancs egy helyen köti össze az állatmenhelyeket az örökbefogadni vágyó
emberekkel. A menhelyek adatlapot kapnak és feltölthetik a kisállataikat, az
érdeklődők keresnek, szűrnek, és az oldalon keresztül üzennek a menhelynek.

A szakdolgozat a rendszer teljes életciklusát mutatja be: adatmodell,
szerveroldali logika, reszponzív felület, szerepköralapú jogosultságkezelés,
képfeltöltés, automatizált tesztelés és éles üzemeltetés automatikus
kihelyezéssel.

## 2. Funkciók és szerepkörök

| Terület | Mit tud |
|---|---|
| Böngészés | Kisállatok és menhelyek listája, keresés név és település szerint, szűrés faj, nem és státusz szerint |
| Adatlapok | Kisállat és menhely adatlap képgalériával |
| Regisztráció | Többlépéses folyamat szerepkörválasztással, élő e-mail-ellenőrzéssel |
| Menhelykezelés | Menhely létrehozása és szerkesztése, képek kezelése |
| Kisállatkezelés | Felvétel, szerkesztés, törlés, státuszváltás |
| Munkatársak | A tulajdonos e-mail cím alapján vesz fel munkatársat, aki ki is léphet |
| Üzenetek | A felhasználó üzen a menhelynek egy kisállatról, a tulajdonos listázza és törli az üzeneteket, az olvasatlanokat a navbar jelzi |
| Fiókbeállítások | Profil és jelszó módosítása, fiók törlése |
| Tudj meg többet | A projekt bemutatása és a legutóbbi e2e futás eredménye |

| Szerepkör | Adatbázis-érték | Jogosultság |
|---|---|---|
| Látogató | nincs fiók | Böngészés |
| Felhasználó | `User` | Böngészés és üzenetküldés |
| Menhely tulajdonos | `Shelterowner` | Saját menhely, kisállatok, munkatársak, üzenetek |
| Munkatárs | `Shelterworker` | A menhelye kisállatainak kezelése |

## 3. Technológiák

| Réteg | Technológia |
|---|---|
| Backend | PHP 8.2+ (a szerveren 8.5), Laravel 11 |
| Dinamikus komponensek | Livewire 3, Alpine.js |
| Felület | Blade, Tailwind CSS 3, Heroicons |
| Asset build | Vite 6 |
| Adatbázis | MySQL 8 |
| Backend tesztek | Pest 3, memóriában futó SQLite adatbázissal |
| E2E tesztek | Playwright 1.64, TypeScript, Chromium és Firefox |
| Kódstílus | Laravel Pint, ESLint, Prettier |
| Szerver | DigitalOcean Droplet, Ubuntu 24.04, nginx, PHP-FPM, MySQL, certbot |
| CI/CD | GitHub Actions |
| Csomagkezelés | Composer, npm workspaces (monorepo) |
| AI asszisztens | Claude Code |

## 4. AI-val gyorsított fejlesztés

A projekt AI-val gyorsított fejlesztéssel készült: a munka során AI
kódasszisztens (Claude Code) segített a kódírásban, a refaktorálásban, a
tesztek írásában, a hibakeresésben, a szerver és a deploy beállításában,
valamint a dokumentációban.

Az AI-val való hatékony munka ma a szoftverfejlesztő alapkészségei közé
tartozik, ezért a szakdolgozat ezt is be kívánja mutatni:

- **Pontos feladatmegadás.** Az eredmény minősége a megfogalmazott célon és a
  megadott kontextuson múlik.
- **Ellenőrzés.** Az AI által írt kód csak akkor került be, ha a tesztek
  (274 backend teszt, 23 e2e forgatókönyv) és a kódstílus-ellenőrzés átment,
  és a szerző átnézte.
- **Felelősség.** A tervezési döntések (architektúra, adatmodell,
  jogosultságok, hosting) a szerzőé, az AI ezek kivitelezését gyorsítja.

A gyakorlati haszon: egy fejlesztő rövidebb idő alatt jut el egy tesztelt,
élesben futó, dokumentált alkalmazásig, és több idő marad a tervezésre.

## 5. Tervezés és megvalósítás

### Architektúra

Szerveroldalon renderelt Laravel MVC alkalmazás. A kérés útja: route,
middleware, controller, Eloquent modell, Blade nézet. Ahol az oldalnak
újratöltés nélkül kell reagálnia, Livewire komponens dolgozik, a kisebb
böngészőoldali interakciókat (menük, galéria, modális ablakok) Alpine.js kezeli.

| Livewire komponens | Feladata |
|---|---|
| `RegisterForm` | Regisztrációs űrlap élő e-mail-ellenőrzéssel |
| `ImageUploader` | Képfeltöltés előnézettel, darabszám- és méretkorláttal |
| `PetFilter` | Szűrőgombok a kisállatlistán |
| `PetSpeciesBreedSelect` | Egymástól függő faj- és fajtaválasztó |

### Adatmodell

| Tábla | Tartalom | Kapcsolat |
|---|---|---|
| `users` | Fiókok, a `type` mező a szerepkör | A munkatárs `shelter_id` mezője a menhelyére mutat |
| `shelter` | Menhelyek, a képek JSON mezőben | `owner_id` a tulajdonos |
| `species`, `breeds` | Fajok és fajták | Egy fajhoz több fajta tartozik |
| `pets` | Kisállatok, a képek JSON mezőben | Menhely, faj, fajta, felvevő felhasználó |
| `form_messages` | A menhelynek küldött üzenetek | Felhasználó, menhely, kisállat |
| `likes`, `comments`, `adoptions` | Előkészített táblák | Felület még nem épül rájuk |

Az URL-ekben sorszám helyett `uuid` szerepel (`HasUuid` trait), így az
azonosítók nem találgathatók. A külső kulcsok kaszkádolva törölnek: a
tulajdonossal törlődik a menhelye, a kisállatai és az üzenetei.

### Jogosultságkezelés

1. `auth` middleware: bejelentkezéshez kötött útvonalak.
2. `role` middleware (`EnsureUserHasRole`): szerepkörhöz kötött útvonalak.
3. `ShelterPolicy`: az adott menhelyhez van-e joga a felhasználónak.
   Szerkeszteni és munkatársat kezelni csak a tulajdonos tud, kisállatot a
   tulajdonos és a munkatársak.

### Képfeltöltés

Az `ImageUploader` ideiglenes mappába tölt fel, mentéskor az
`UploadDisk::moveTempImages()` mozgatja a képeket a végleges helyükre:

```
storage/app/public/temp/<pet|shelter>/     feltöltés közben
storage/app/public/pets/<menhely>/<kisállat>/
storage/app/public/shelters/<menhely>/
```

### Ütemezett feladatok

| Parancs | Időpont | Feladat |
|---|---|---|
| `uploads:prune` | naponta 00:00 | A 24 óránál régebbi ideiglenes feltöltések törlése |
| `demo:reset --force` | naponta 04:00 | Minden adat törlése és a seedelt állapot visszaállítása |

A `demo:reset` miatt az éles oldal minden reggel ugyanabban az ismert
állapotban van, a látogatók regisztrációi szándékosan nem maradnak meg.

A felület magyar nyelvű, a fordítások az `app/lang/hu` mappában vannak.

## 6. Tesztelés

| Szint | Eszköz | Mit ellenőriz | Darab |
|---|---|---|---|
| Unit és feature | Pest | Modellek, policy, middleware, validáció, controllerek, útvonalak, Livewire komponensek, parancsok | 274 teszt |
| E2E | Playwright | Teljes felhasználói folyamatok valódi böngészőben (Chromium, Firefox) | 23 forgatókönyv, 46 futás |

A backend tesztek memóriában futó SQLite adatbázist használnak, és a deploy
előfeltételei. Az e2e tesztek három rétegűek (spec, lépések, page objectek), a
helyi és az éles oldal ellen is futtathatók, és a létrehozott adatokat maguk
után törlik. Az utolsó futás eredménye az oldal „Tudj meg többet" lapján
látszik.

## 7. Használható felhasználók

Alapfiókok (`UserSeeder`, a `migrate --seed` hozza létre, az e2e tesztek is
ezeket használják):

| Szerepkör | E-mail | Jelszó |
|---|---|---|
| Felhasználó | `user@example.com` | `RegularUserPassword123` |
| Menhely tulajdonos | `shelterowner@example.com` | `ShelterOwnerPassword123` |
| Munkatárs | `shelterworker@example.com` | `ShelterWorkerPassword123` |
| Fejlesztő | `developer@example.com` | `DeveloperPassword123` |

Demo fiókok (`DemoSeeder`, helyben a `demo:seed` vagy a `demo:reset` tölti be):

| Szerepkör | E-mail | Jelszó |
|---|---|---|
| Menhely tulajdonos | `demo.owner@example.com` | `DemoPassword123` |
| Munkatárs | `demo.worker@example.com` | `DemoPassword123` |
| Felhasználó | `demo.user@example.com` | `DemoPassword123` |

Mindkét készlet az éles oldalon is elérhető. A munkatárs alapfiók nincs
menhelyhez rendelve, a fejlesztő fiókhoz nem tartozik külön jogosultság. A demo
tulajdonosé a „Tappmancs Demo Menhely" három kisállattal.

A teljes seedelt állomány: 28 felhasználó, 28 menhely, 57 kisállat.

## 8. A repó felépítése

npm workspaces monorepo két csomaggal. A futtatás részletei a csomagok saját
leírásában vannak:

| Mappa | Tartalom | Részletek |
|---|---|---|
| `app/` | Laravel alkalmazás | [app/README.md](app/README.md): fájlstruktúra, helyi futtatás a nulláról, parancsok |
| `app-e2e/` | Playwright e2e tesztek | [app-e2e/README.md](app-e2e/README.md): fájlstruktúra, beállítás, futtatás, takarítás |
| `.github/workflows/` | GitHub Actions deploy | lásd a Deploy részt |

Gyors indítás a telepítés után, a gyökérből: `npm run dev` (alkalmazás),
`npm run test` (backend tesztek), `npm run test:e2e` (e2e tesztek).

## 9. Az éles szerver

| | |
|---|---|
| Szolgáltató | DigitalOcean Droplet, Frankfurt |
| Méret | 1 vCPU, 1 GB RAM, 25 GB SSD, 2 GB swap |
| Operációs rendszer | Ubuntu 24.04 LTS |
| Webszerver | nginx, PHP-FPM 8.5 |
| Adatbázis | MySQL 8.0, ugyanazon a gépen |
| Képek | A gép lemezén (`storage/app/public`) |
| TLS | Let's Encrypt (certbot), automatikus megújítással |
| Domain | `tappmancs-szakdolgozat.hu` |
| Alkalmazás helye | `/var/www/tappmancs` |

A szolgáltatások a gép indulásakor maguktól elindulnak, az alkalmazást az nginx
szolgálja ki PHP-FPM-en keresztül.

## 10. Az éles adatbázis elérése

A MySQL kívülről nem érhető el (a 3306-os port zárva van), ezért SSH-alagút
kell hozzá (az IP a DigitalOcean felületén és a `DEPLOY_HOST` secretben van).
Indítsd el, és hagyd futni:

```bash
ssh -N -L 3307:127.0.0.1:3306 deploy@<droplet-ip>
```

Ezután parancssorból:

```bash
mysql -h 127.0.0.1 -P 3307 -u tappmancs -p tappmancs
```

Grafikus kliensből (TablePlus, DBeaver) sima TCP kapcsolattal, a kliens saját
SSH beállítása nélkül:

| Mező | Érték |
|---|---|
| Host | `127.0.0.1` |
| Port | `3307` |
| Felhasználó | `tappmancs` |
| Adatbázis | `tappmancs` |
| Jelszó | a szerver `/var/www/tappmancs/app/.env` fájljában (`DB_PASSWORD`) |

Közvetlenül a szerveren: `mysql -u tappmancs -p tappmancs`.

## 11. Deploy

A GitHub Actions minden `main` ágra érkező pushnál kihelyez
(`.github/workflows/deploy.yml`). A szakaszok az Actions felületén külön
csomópontként látszanak:

| Szakasz | Mit csinál |
|---|---|
| Build assets | A runneren lefordítja a CSS-t és a JavaScriptet |
| Run tests | Lefuttatja a backend teszteket, hiba esetén a szerverhez semmi nem nyúl |
| Pull code | SSH-n frissíti a kódot, és telepíti a PHP függőségeket |
| Upload assets | Feltölti a lefordított asseteket |
| Migrate and cache | Migráció, cache-ek újraépítése, PHP-FPM újratöltése |
| Seed demo data | Csak kézi indításnál fut, `seed: true` beállítással |

Az e2e tesztek nem részei a deploynak, azok a kihelyezett oldal ellen futnak.
Kézi indítás: Actions fül, Deploy workflow, „Run workflow".

## 12. A projekt időrendje

| Időszak | Mi készült |
|---|---|
| 2024. december | Laravel projekt, adatmodell, migrációk, Eloquent kapcsolatok |
| 2025. február és március | Többlépéses regisztráció, nyitóoldal, bejelentkezés |
| 2025. június és szeptember | Felhasználói oldalak, az e2e tesztkörnyezet alapjai, kódstílus-ellenőrzés |
| 2026. február | A 2024/25-ös tanév kódállapotának rögzítése |
| 2026. március és április | Kisállatlista, adatlap, szerkesztés és törlés, menhely adatlap galériával, monorepo, hibaoldalak |
| 2026. május | Keresés és szűrés, munkatárskezelés |
| 2026. június | Üzenetküldés, GitHub Actions deploy, mobilnézet, unit tesztek |
| 2026. október | DigitalOcean Droplet, saját domain és TLS, MySQL, napi demo-visszaállítás, e2e az éles oldal ellen, szakaszokra bontott deploy tesztkapuval, kódtisztítás |

## 13. Továbbfejlesztési lehetőségek

- Kedvelések, hozzászólások és örökbefogadási folyamat (a táblák és a modellek
  már léteznek).
- Több menhely kezelése egy tulajdonossal.
- Adminisztrátori szerepkör (a `Developer` típus létezik, jogosultság nélkül).
- A kisállat nemének megjelenítése az adatlapon.
- Válasz az üzenetekre az oldalon belül, e-mail értesítés új üzenetről.
- Kedvencek listája és értesítés státuszváltozásról.
