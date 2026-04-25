# Tappmancs

Pet shelter adoption management system. Monorepo with two workspaces:

- **`app/`** — Laravel 11 application (PHP, Livewire, Tailwind, Vite)
- **`app-e2e/`** — Playwright end-to-end tests

## Setup

```bash
# Install JS deps for both workspaces
npm install

# Install PHP deps (run inside app/)
cd app && composer install && cd ..

# Set up the Laravel app
cp app/.env.example app/.env
cd app && php artisan key:generate && php artisan storage:link && cd ..
```

## Common commands

Run from the repo root:

| Command              | What it does                                 |
| -------------------- | -------------------------------------------- |
| `npm run dev`        | Start Laravel + Vite dev servers             |
| `npm run build`      | Build production assets                      |
| `npm run test`       | Run all PHPUnit tests                        |
| `npm run test:e2e`   | Run Playwright tests against the dev server  |
| `npm run lint`       | Run Pint (PHP code formatter)                |

For commands not exposed at the root, `cd` into the workspace and run directly.
