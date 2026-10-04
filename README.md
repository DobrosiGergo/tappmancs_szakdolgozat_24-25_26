# Tappmancs Szakdolgozat (2024–26)

Állatmenhelyek és örökbefogadható kisállatok kezelésére szolgáló webalkalmazás.

## Monorepo struktúra

| Könyvtár | Leírás |
|---|---|
| `app/` | Laravel 11 + Livewire 3 backend + frontend |
| `app-e2e/` | Playwright végponttól végpontig tesztek |

## Követelmények

- PHP 8.2+
- Node.js 18+
- Composer

## Telepítés

```bash
npm install
cd app && composer install
cp app/.env.example app/.env
cd app && php artisan key:generate && php artisan migrate --seed
```

## Helyi fejlesztés

```bash
npm run dev
```

Az alkalmazás a [http://localhost:8000](http://localhost:8000) címen érhető el.

## Parancsok

| Parancs | Leírás |
|---|---|
| `npm run dev` | Laravel + Vite fejlesztői szerver indítása |
| `npm run build` | Frontend eszközök éles fordítása |
| `npm run test` | Unit és feature tesztek futtatása |
| `npm run test:e2e` | Playwright e2e tesztek futtatása lokálisan (kell hozzá a futó `npm run dev`) |
| `npm run test:e2e:prod` | E2e tesztek a production ellen (`E2E_BASE_URL` kötelező, adatmódosító tesztek kimaradnak) |
| `npm run lint` | Kódstílus ellenőrzése (Laravel Pint) |

## Deploy

Az alkalmazás a **Vercelen** fut serverless functionként; a deployt a Vercel
Git-integrációja végzi minden `main`-re érkező pushnál. Az adatbázis **Aiven
MySQL**, a feltöltött képek **Cloudflare R2** bucketben vannak.
Az éles cím: **https://tappmancs-szakdolgozat.hu**

A teljes beállítás (Aiven, R2, env változók, domain, cron, seedelés, és az
AWS leállítása) a
[docs/VERCEL.md](docs/VERCEL.md)-ben van leírva.

> A dev és a prod ugyanazt a motort használja: lokálisan is **MySQL** megy
> (`app/.env.example`), hogy ne térjenek el. A feltöltés diskje viszont
> környezetfüggő: lokálisan a `public` disk, prodban `s3` — ezt az
> `UPLOADS_DISK` env változó vezérli.

## E2E tesztek

A Playwright tesztek az `app-e2e/` mappában találhatók (page object minta, `src/ui/po/` + `src/ui/features/`).

```bash
# Egyszeri beállítás: a példafájl értékei a seedelt felhasználókhoz passzolnak
cp app-e2e/.env.example app-e2e/.env

# Lokálisan (előtte: npm run dev egy másik terminálban)
npm run test:e2e

# Production ellen (csak nem-adatmódosító tesztek futnak)
E2E_BASE_URL=https://tappmancs-szakdolgozat.hu \
E2E_USER_EMAIL=<teszt-user-email> \
E2E_USER_PASSWORD=<teszt-user-jelszo> \
E2E_USER_NAME="<teszt-user-nev>" \
npm run test:e2e:prod
```

### Tesztriport a „Tudj meg többet" oldalon

A `/about` oldal „Automatizált tesztelés" szekciója az `app/resources/data/e2e-results.json` fájlból olvas, amit a teszt-futás generál:

```bash
cd app-e2e && npm run test:e2e:export
```

A fájl **verziókövetett** — enélkül a szekció a deployolt oldalon nem jelenik meg. A szekciócímek (`Bejelentkezés`, `Regisztráció`, …) az `app-e2e/scripts/export-results.mjs` `AREAS` táblájából jönnek, spec-fájlnév szerint; új spec esetén a script hibával áll le, amíg be nem kerül a megfelelő sor.

Környezeti változók: `E2E_TARGET` (`local`/`production`), `E2E_BASE_URL`, valamint a teszt fiókok (`E2E_USER_*`, `E2E_OWNER_*`, `E2E_WORKER_*`). Az értékek az `app-e2e/.env` fájlból jönnek (lásd `.env.example`), a shell változók felülírják őket. A beolvasást és a validációt az `app-e2e/src/config/env.ts` végzi — hiányzó változónál azonnal, beszédes hibával áll le.

A tesztek tagekkel futtathatók (`npx playwright test --grep @tag`):

| Tag | Tartalom |
|---|---|
| `@smoke` | Főoldal, „Tudj meg többet" oldal + bejelentkezés/kijelentkezés gyorsteszt |
| `@home`, `@about`, `@auth`, `@registration` | Főoldal, „Tudj meg többet" oldal, auth és regisztrációs flow-k |
| `@shelter`, `@staffing`, `@worker` | Menhely kezelés, munkatárs kezelés, dolgozói interakciók |
| `@user`, `@settings` | Felhasználói böngészés, profil beállítások |
| `@mutating` | Adatot módosító tesztek — a `test:e2e:prod` script kizárja őket |
