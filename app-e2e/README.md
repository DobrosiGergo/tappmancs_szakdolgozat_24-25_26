# Tappmancs: e2e tesztek

Ez a mappa a Tappmancs böngészős, végponttól végpontig tartó tesztjeit
tartalmazza (Playwright, TypeScript, Chromium és Firefox): 23 forgatókönyv, két
böngészőben 46 futás. A projekt leírása a repó gyökerében lévő
[README.md](../README.md) fájlban, az alkalmazás helyi futtatása az
[app/README.md](../app/README.md) fájlban található.

## Fájlstruktúra

```
app-e2e/
  playwright.config.ts        Helyi futtatás (alapértelmezett)
  playwright.config.prod.ts   Futtatás az éles oldal ellen
  playwright.config.base.ts   A két konfiguráció közös része
  scripts/
    export-results.mjs        A futás eredményét az /about oldal számára menti
  src/
    config/
      env.ts                  Környezeti változók és a tesztelt oldal címe
      global-setup.ts         Futás előtt ellenőrzi, hogy az oldal elérhető-e
    fixtures/
      test.ts                 A lépésobjektumok és a createUser fixture
    ui/
      features/               A tesztek (spec fájlok), területenként
      po/                     Page objectek: az oldalak szelektorai
      po/components/          Újrahasznosított oldalrészek (navbar, kártyák)
      steps/                  A tesztlépések, oldalanként
    utils/                    Feliratok, útvonalak, tesztadatok
```

Három réteg: a spec fájlok lépéseket hívnak, a lépések page objecteket
használnak, a szelektorok csak a page objectekben élnek.

## Beállítás

A repó gyökeréből, `npm install` után:

```bash
cp app-e2e/.env.example app-e2e/.env
cd app-e2e
npx playwright install chromium firefox
cd ..
```

Az `.env` alapértékei a seedelt alapfiókokra mutatnak.

## Futtatás

A parancsok a repó gyökeréből és ebből a mappából is működnek.

Helyben az egyik terminálban fusson az alkalmazás seedelt adatbázissal
(`npm run dev`), a másikban:

```bash
npm run test:e2e
```

Az éles oldal ellen:

```bash
npm run test:e2e:prod
```

A helyi konfiguráció nem futtatható távoli cím ellen, és fordítva.

## Parancsok ebből a mappából

| Parancs | Mit csinál |
|---|---|
| `npm run test:e2e` | Tesztek a helyi szerver ellen |
| `npm run test:e2e:prod` | Tesztek az éles oldal ellen |
| `npm run test:e2e:smoke` | Csak a `@smoke` címkéjű gyorstesztek |
| `npm run test:e2e:headed` | Látható böngészőablakkal fut |
| `npm run test:e2e:ui` | Grafikus felület, lépésenkénti futtatás |
| `npm run test:e2e:list` | A tesztek listázása futtatás nélkül |
| `npx playwright test --grep @shelter` | Egy terület tesztjei címke alapján |
| `npx playwright show-report` | Az utolsó futás HTML riportja |
| `npm run test:e2e:export` | Frissíti a „Tudj meg többet" oldalon megjelenő eredményt |
| `npm run lint` | ESLint ellenőrzés |
| `npm run format` | Prettier ellenőrzés |

Címkék: `@smoke`, `@home`, `@about`, `@auth`, `@registration`, `@settings`,
`@shelter`, `@staffing`, `@user`, `@worker`.

## Takarítás

1. A `createUser` fixture a teszt végén, sikertől függetlenül, az oldal saját
   fióktörlő felületén törli a létrehozott fiókot, vele a menhelyét, a
   kisállatait és az üzeneteit.
2. A seedelt fiókokkal dolgozó tesztek visszaállítják a kiinduló állapotot.
3. Ami mégis megmarad, azt a hajnali `demo:reset` törli.
