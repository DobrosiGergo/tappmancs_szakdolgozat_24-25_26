# Tappmancs: Laravel alkalmazás

Ez a mappa a Tappmancs webalkalmazást tartalmazza (Laravel 11, Livewire 3,
Alpine.js, Tailwind CSS). A projekt leírása, a tervezés és megvalósítás, valamint
a használható felhasználók a repó gyökerében lévő [README.md](../README.md)
fájlban találhatók, az e2e tesztek leírása az
[app-e2e/README.md](../app-e2e/README.md) fájlban.

## Fájlstruktúra

```
app/
  app/
    Console/Commands/     demo:reset, demo:seed, uploads:prune
    Helpers/              Tools (flash üzenet)
    Http/
      Controllers/        Pet, Shelter, Staffing, Form, Settings, Welcome
      Controllers/Auth/   Bejelentkezés, regisztrációs lépések, jelszókezelés
      Middleware/         EnsureUserHasRole
      Requests/           Űrlapvalidáció
    Livewire/             RegisterForm, ImageUploader, PetFilter, PetSpeciesBreedSelect
    Models/               User, Shelter, Pet, Specie, Breed, Form, Like, Comment, Adoption
    Policies/             ShelterPolicy
    Support/              UploadDisk
    View/Components/      Elrendezések és navigáció
  bootstrap/              Az alkalmazás indítása, middleware-aliasok
  config/                 Konfiguráció
  database/
    factories/            Tesztadat-gyárak
    migrations/           Táblák
    seeders/              Alap- és demo adatok
  lang/hu/                Magyar fordítások
  public/                 Webgyökér, képek, a lefordított assetek (build/)
  resources/
    css/, js/             Tailwind belépési pont, Alpine komponensek
    data/                 e2e-results.json, a legutóbbi e2e futás eredménye
    views/                Blade nézetek és komponensek
  routes/
    web.php               Publikus és hitelesített útvonalak
    auth.php              Hitelesítési útvonalak
    console.php           Ütemezett feladatok
  storage/                Feltöltött képek, naplók, cache
  tests/
    Feature/              Controller-, útvonal-, parancs- és Livewire-tesztek
    Unit/                 Modell-, policy-, middleware- és request-tesztek
```

## Helyi futtatás a nulláról

Előfeltételek: PHP 8.2+ (`pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`,
`bcmath`, `intl`), Composer 2, Node 20+, MySQL 8+, Git.

```bash
# 1. A kód letöltése
git clone https://github.com/DobrosiGergo/tappmancs_szakdolgozat_24-25_26.git
cd tappmancs_szakdolgozat_24-25_26

# 2. JavaScript függőségek (a gyökérben, mindkét csomaghoz)
npm install

# 3. PHP függőségek
cd app
composer install

# 4. Környezeti fájl és alkalmazáskulcs
cp .env.example .env
php artisan key:generate

# 5. Adatbázis (ha nem jelszó nélküli root a felhasználód,
#    írd át a DB_USERNAME és DB_PASSWORD értékét az app/.env fájlban)
mysql -u root -e 'create database tappmancs'

# 6. Táblák és alapadatok
php artisan migrate --seed

# 7. Demo fiókok és demo menhely (nem kötelező)
php artisan demo:seed

# 8. A feltöltött képek kiszolgálása
php artisan storage:link

# 9. Indítás a repó gyökeréből (PHP szerver és Vite egyszerre)
cd ..
npm run dev
```

Az alkalmazás a **http://localhost:8000** címen érhető el. Az e-mailek nem
mennek ki, az `app/storage/logs/laravel.log` fájlba íródnak.

## Parancsok

A repó gyökeréből:

| Parancs | Mit csinál |
|---|---|
| `npm run dev` | PHP szerver és Vite indítása |
| `npm run build` | Az assetek éles fordítása |
| `npm run test` | Minden backend teszt |
| `npm run test:unit`, `test:feature` | Csak a unit vagy a feature tesztek |
| `npm run test:coverage` | Lefedettségi riport a `coverage-html` mappába |
| `npm run lint` | PHP kódstílus javítása (Pint) |

Ebből a mappából:

| Parancs | Mit csinál |
|---|---|
| `npm run dev:db` | Az adatbázis újraépítése és újraseedelése |
| `php artisan demo:reset` | Minden adat törlése, majd alap- és demo adatok betöltése |
| `php artisan demo:seed` | A három demo fiók és a demo menhely betöltése |
| `php artisan uploads:prune` | A félbehagyott feltöltések törlése |

## Tesztelés

| Szint | Mit ellenőriz |
|---|---|
| Unit | Modellek, policy, middleware, űrlapvalidáció |
| Feature | Controllerek, útvonalak, jogosultságok, Livewire komponensek, parancsok |

274 teszt Pesttel. MySQL nem kell hozzájuk, memóriában futó SQLite adatbázist
használnak (`phpunit.xml`):

```bash
npm run test
```
