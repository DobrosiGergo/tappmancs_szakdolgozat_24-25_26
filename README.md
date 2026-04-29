# Tappmancs – Szakdolgozat (2024–26)

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
| `npm run test:e2e` | Playwright e2e tesztek futtatása |
| `npm run lint` | Kódstílus ellenőrzése (Laravel Pint) |
