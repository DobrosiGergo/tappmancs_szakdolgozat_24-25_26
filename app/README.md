# Állatmenhely – Szakdolgozat (2024–26)

Laravel 11 + Livewire 3 + Alpine.js + Tailwind CSS alapú webalkalmazás állatmenhelyek és örökbefogadható kisállatok kezelésére.

## Követelmények

- PHP 8.2+
- Node.js 18+
- Composer

## Telepítés

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Helyi fejlesztés

```bash
npm run dev
```

Ez a parancs egyszerre indítja el a PHP fejlesztői szervert (`php artisan serve`) és a Vite HMR szervert. Az alkalmazás a [http://localhost:8000](http://localhost:8000) címen érhető el.

## Egyéb parancsok

| Parancs | Leírás |
|---|---|
| `npm run dev:db` | Adatbázis visszaállítása és újraseedelése |
| `npm run build` | Frontend eszközök éles fordítása |
| `npm run test:all` | Összes teszt futtatása |
| `npm run lint:pint` | Kódstílus ellenőrzése (Laravel Pint) |
