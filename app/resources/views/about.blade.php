<x-guest-layout>
  @section('title', 'Tudj meg többet')

  <section class="bg-neutral-900 text-white" data-test-id="about-hero">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-20 md:py-28">
      <span class="text-[11px] uppercase tracking-[0.2em] text-white/40">Szakdolgozat projekt</span>
      <h1 class="mt-3 text-4xl md:text-6xl font-semibold tracking-tight leading-tight max-w-3xl"
          data-test-id="about-title">
        Tappmancs
      </h1>
      <h2 class="mt-3 text-2xl md:text-3xl font-semibold tracking-tight leading-tight max-w-3xl">
        állatmenhelyek és örökbefogadók egy platformon
      </h2>
      <p class="mt-6 text-base md:text-lg text-white/60 leading-relaxed max-w-2xl">
        A Tappmancs egy webalkalmazás, amelynek célja összekötni az állatmenhelyeket az örökbefogadni vágyó emberekkel.
        A platform lehetővé teszi a menhelyek kezelését, kisállatok feltöltését, és az érdeklődők közvetlen kapcsolatfelvételét.
      </p>
      <div class="mt-8 flex flex-wrap gap-4">
        <a href="{{ route('pets.index') }}"
           class="inline-flex items-center gap-2 rounded-full bg-white text-neutral-900 px-6 py-3 text-sm font-semibold hover:bg-neutral-100 transition"
           data-test-id="about-pets-link">
          Kisállatok böngészése
        </a>
        <a href="{{ route('shelters.index') }}"
           class="inline-flex items-center gap-2 rounded-full bg-white/10 text-white px-6 py-3 text-sm font-semibold ring-1 ring-white/20 hover:bg-white/15 transition"
           data-test-id="about-shelters-link">
          Menhelyek megtekintése
        </a>
      </div>
    </div>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white" data-test-id="about-features">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">Főbb funkciók</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-0">
        @foreach([
          ['01', 'Kisállatok böngészése', 'Kereshetsz és szűrhetsz faj, fajta, nem és státusz szerint. Minden kisállathoz részletes adatlap tartozik képgalériával.'],
          ['02', 'Menhelyek és kapcsolatfelvétel', 'A menhelyek publikus adatlapján megtalálod az elérhetőségeket és a kisállatokat. Az oldalon beépített üzenetküldőn keresztül közvetlenül érdeklődhetsz az örökbefogadásról.'],
          ['03', 'Menhely kezelése', 'Menhely tulajdonosként létrehozhatsz és szerkeszthetsz menhelyet, feltölthetsz kisállatokat, és meghívhatod a munkatársaidat. A munkatársak szintén kezelhetik a kisállatokat.'],
        ] as [$num, $title, $desc])
          <div class="group relative py-10 md:py-0 md:px-10 first:md:pl-0 last:md:pr-0
                       border-t border-white/10 md:border-t-0 first:border-t-0
                       md:border-l first:md:border-l-0">
            <span class="block font-light text-[80px] leading-none text-white/[0.07] select-none mb-5
                         group-hover:text-white/[0.13] transition-colors duration-700">
              {{ $num }}
            </span>
            <h3 class="text-lg font-semibold text-white mb-3 leading-snug">{{ $title }}</h3>
            <p class="text-sm leading-relaxed text-white/50 max-w-xs">{{ $desc }}</p>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <section class="bg-white py-20 md:py-28" data-test-id="about-roles">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400 shrink-0">Felhasználói szerepkörök</span>
        <div class="flex-1 h-px bg-neutral-200"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
          ['Látogató / Felhasználó', 'Regisztráció után böngészhetsz kisállatok és menhelyek között, és üzenetet küldhetsz a menhelyeknek az örökbefogadással kapcsolatban.'],
          ['Menhely tulajdonos', 'Létrehozhatod és szerkesztheted a menhelyed adatlapját, feltölthetsz kisállatokat, és hívhatsz meg munkatársakat az adminisztrációhoz.'],
          ['Munkatárs', 'A menhely tulajdonosa által meghívott személy. A menhelyhez tartozó összes kisállatot kezelheti – felveheti, szerkesztheti, és módosíthatja azok státuszát.'],
        ] as [$role, $desc])
          <div class="rounded-2xl border border-neutral-200 bg-white p-8 shadow-sm">
            <h3 class="text-base font-semibold text-neutral-900 mb-3">{{ $role }}</h3>
            <p class="text-sm leading-relaxed text-neutral-500">{{ $desc }}</p>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white" data-test-id="about-accounts">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">Kipróbálható fiókok</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <p class="text-white/60 text-base leading-relaxed max-w-2xl">
        Az oldal bemutató céllal fut, ezért az alábbi fiókokkal bárki kipróbálhatja. Az adatok
        minden hajnalban visszaállnak a kiinduló állapotra, így a saját regisztrációk nem maradnak meg.
      </p>

      <div class="mt-10 divide-y divide-white/[0.07] rounded-2xl ring-1 ring-white/10">
        @foreach([
          ['Menhely tulajdonos', 'demo.owner@example.com', 'DemoPassword123'],
          ['Munkatárs', 'demo.worker@example.com', 'DemoPassword123'],
          ['Felhasználó', 'demo.user@example.com', 'DemoPassword123'],
        ] as [$role, $email, $password])
          <div class="px-5 py-4 flex flex-col gap-1 text-sm sm:flex-row sm:items-center sm:gap-6">
            <span class="sm:w-56 sm:shrink-0 font-medium text-white">{{ $role }}</span>
            <span class="sm:flex-1 font-mono text-white/70 break-all">{{ $email }}</span>
            <span class="font-mono text-white/70">{{ $password }}</span>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <section class="bg-white py-20 md:py-28" data-test-id="about-design">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400 shrink-0">Tervezés és megvalósítás</span>
        <div class="flex-1 h-px bg-neutral-200"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach([
          ['Architektúra', 'Szerveroldalon renderelt Laravel MVC alkalmazás. Ahol az oldalnak újratöltés nélkül kell reagálnia (regisztráció, képfeltöltés, szűrés, faj- és fajtaválasztó), Livewire komponens dolgozik, a kisebb böngészőoldali interakciókat Alpine.js kezeli.'],
          ['Adatmodell', 'Felhasználók, menhelyek, fajok és fajták, kisállatok, üzenetek és értesítések. Az URL-ekben sorszám helyett uuid szerepel, így az azonosítók nem találgathatók. A tulajdonos törlésével törlődik a menhelye, a kisállatai és az üzenetei.'],
          ['Jogosultságkezelés', 'Három szinten történik: a bejelentkezést az auth middleware, a szerepkört a role middleware, az adott menhelyhez való hozzáférést a ShelterPolicy ellenőrzi. Szerkeszteni csak a tulajdonos tud, kisállatot a tulajdonos és a munkatársak.'],
          ['Képfeltöltés', 'A képek először ideiglenes mappába kerülnek, és csak az űrlap mentésekor mozognak a végleges helyükre. A félbehagyott feltöltések fájljait egy ütemezett parancs naponta törli.'],
        ] as [$title, $desc])
          <div class="rounded-2xl border border-neutral-200 bg-white p-8 shadow-sm">
            <h3 class="text-base font-semibold text-neutral-900 mb-3">{{ $title }}</h3>
            <p class="text-sm leading-relaxed text-neutral-500">{{ $desc }}</p>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white" data-test-id="about-tech">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">Technológiák</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <dl class="divide-y divide-white/[0.07] border-y border-white/[0.07]">
        @foreach([
          ['Backend', 'PHP 8.4, Laravel 11'],
          ['Dinamikus komponensek', 'Livewire 3, Alpine.js'],
          ['Felület', 'Blade, Tailwind CSS 3, Heroicons, Vite 6'],
          ['Adatbázis', 'MySQL 8'],
          ['Backend tesztek', 'Pest 3'],
          ['E2E tesztek', 'Playwright, TypeScript, Chromium és Firefox'],
          ['Kódstílus', 'Laravel Pint, ESLint, Prettier'],
          ['Szerver', 'DigitalOcean Droplet, Ubuntu 24.04, nginx, PHP-FPM'],
          ['CI/CD', 'GitHub Actions'],
          ['AI asszisztens', 'Claude Code'],
        ] as [$layer, $tools])
          <div class="py-4 flex flex-col gap-1 sm:flex-row sm:gap-6">
            <dt class="sm:w-56 sm:shrink-0 text-sm font-medium text-white">{{ $layer }}</dt>
            <dd class="text-sm text-white/50">{{ $tools }}</dd>
          </div>
        @endforeach
      </dl>

    </div>
  </section>

  <section class="bg-white py-20 md:py-28" data-test-id="about-ai">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400 shrink-0">AI-val gyorsított fejlesztés</span>
        <div class="flex-1 h-px bg-neutral-200"></div>
      </div>

      <p class="text-neutral-600 text-base leading-relaxed max-w-2xl">
        A projekt AI-val gyorsított fejlesztéssel készült: AI kódasszisztens segített a kódírásban,
        a refaktorálásban, a tesztek írásában, a hibakeresésben, az üzemeltetés beállításában és a
        dokumentációban. Az AI-val való hatékony munka ma a szoftverfejlesztő alapkészségei közé
        tartozik, ezért a szakdolgozat ezt is bemutatja.
      </p>

      <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
          ['Pontos feladatmegadás', 'Az eredmény minősége a megfogalmazott célon és a megadott kontextuson múlik.'],
          ['Ellenőrzés', 'Az AI által írt kód csak akkor került be, ha a tesztek és a kódstílus-ellenőrzés átment, és a szerző átnézte.'],
          ['Felelősség', 'A tervezési döntések a szerzőé, az AI ezek kivitelezését gyorsítja.'],
        ] as [$title, $desc])
          <div class="rounded-2xl border border-neutral-200 bg-white p-8 shadow-sm">
            <h3 class="text-base font-semibold text-neutral-900 mb-3">{{ $title }}</h3>
            <p class="text-sm leading-relaxed text-neutral-500">{{ $desc }}</p>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  @if($e2e)
    <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white" data-test-id="about-e2e">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <div class="mb-14 flex items-center gap-5">
          <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">Automatizált tesztelés</span>
          <div class="flex-1 h-px bg-white/10"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">

          <div>
            <p class="text-white/60 text-base leading-relaxed max-w-xl">
              A felület működését Playwright alapú végponttól végpontig tesztek ellenőrzik, valódi
              böngészőben. A tesztkód page object mintát követ: a szelektorok az oldalosztályokban
              élnek, a forgatókönyvek pedig külön lépésrétegben, így egy felületi változás egy helyen
              javítható.
            </p>

            <dl class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-y-8 gap-x-4">
              @foreach([
                ['Forgatókönyv', $e2e['scenarios']],
                ['Futtatás', $e2e['runs']],
                ['Sikeres', $e2e['passed'] . '/' . $e2e['runs']],
                ['Böngésző', count($e2e['browsers'])],
              ] as [$label, $value])
                <div>
                  <dd class="text-3xl font-semibold tracking-tight text-white">{{ $value }}</dd>
                  <dt class="mt-1 text-[11px] uppercase tracking-[0.18em] text-white/40">{{ $label }}</dt>
                </div>
              @endforeach
            </dl>

            <div class="mt-10 overflow-hidden rounded-2xl ring-1 ring-white/10 bg-black/40">
              <div class="flex items-center gap-2 border-b border-white/10 px-4 py-3">
                <span class="h-2.5 w-2.5 rounded-full bg-white/20"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/20"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/20"></span>
                <span class="ml-2 text-[11px] text-white/40 font-mono">npm run test:e2e</span>
              </div>
              <div class="overflow-x-auto">
                <pre class="px-4 py-4 text-[12px] leading-relaxed font-mono text-white/70"><span class="text-white/40">$</span> npx playwright test
Running {{ $e2e['runs'] }} tests using 1 worker

@foreach($e2e['areas'] as $area)<span class="text-green-400">  ✓</span> {{ $area['area'] }} <span class="text-white/30">({{ count($area['cases']) }})</span>
@endforeach
<span class="text-green-400">  {{ $e2e['passed'] }} passed</span> <span class="text-white/30">({{ $e2e['durationSeconds'] }}s)</span></pre>
              </div>
              <div class="border-t border-white/10 px-4 py-3 text-[11px] text-white/35">
                {{ \Illuminate\Support\Carbon::parse($e2e['startedAt'])->format('Y. m. d. H:i') }} ·
                {{ implode(', ', $e2e['browsers']) }}
                @if($e2e['failed'] > 0) · <span class="text-red-400">{{ $e2e['failed'] }} hibás</span> @endif
              </div>
            </div>
          </div>

          <div>
            <h3 class="text-[11px] uppercase tracking-[0.18em] text-white/40 mb-6">Mit fednek le a tesztek</h3>
            <div class="divide-y divide-white/[0.07] border-y border-white/[0.07]">
              @foreach($e2e['areas'] as $area)
                <div class="py-5 flex flex-col gap-2 sm:flex-row sm:gap-6">
                  <span class="sm:w-40 sm:shrink-0 text-sm font-medium text-white">{{ $area['area'] }}</span>
                  <ul class="flex-1 space-y-1.5">
                    @foreach($area['cases'] as $case)
                      <li class="text-sm text-white/50 leading-relaxed">{{ $case }}</li>
                    @endforeach
                  </ul>
                </div>
              @endforeach
            </div>
          </div>

        </div>

      </div>
    </section>
  @endif

  <section class="bg-white py-20 md:py-28" data-test-id="about-ops">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400 shrink-0">Üzemeltetés és kihelyezés</span>
        <div class="flex-1 h-px bg-neutral-200"></div>
      </div>

      <p class="text-neutral-600 text-base leading-relaxed max-w-2xl">
        Az oldal egy frankfurti DigitalOcean Dropleten fut (Ubuntu 24.04, nginx, PHP-FPM 8.4, MySQL 8),
        saját domainnel és Let's Encrypt tanúsítvánnyal. A kihelyezést a GitHub Actions végzi a
        main ágra érkező minden változásnál, egymásra épülő szakaszokban.
      </p>

      <ol class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
          ['Build', 'A CSS és a JavaScript lefordítása'],
          ['Tesztek', 'A backend tesztek futtatása, hiba esetén a szerverhez semmi nem nyúl'],
          ['Kód', 'A kód frissítése a szerveren'],
          ['Assetek', 'A lefordított fájlok feltöltése'],
          ['Migráció', 'Adatbázis-migráció és a cache-ek újraépítése'],
        ] as [$stage, $desc])
          <li class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm">
            <span class="text-[11px] uppercase tracking-[0.18em] text-neutral-400">{{ $loop->iteration }}. lépés</span>
            <h3 class="mt-2 text-base font-semibold text-neutral-900">{{ $stage }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{ $desc }}</p>
          </li>
        @endforeach
      </ol>

    </div>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white" data-test-id="about-timeline">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">A projekt időrendje</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <div class="divide-y divide-white/[0.07] border-y border-white/[0.07]">
        @foreach([
          ['2024. december', 'Laravel projekt, adatmodell, migrációk, Eloquent kapcsolatok'],
          ['2025. február és március', 'Többlépéses regisztráció, nyitóoldal, bejelentkezés'],
          ['2025. június és szeptember', 'Felhasználói oldalak, az e2e tesztkörnyezet alapjai, kódstílus-ellenőrzés'],
          ['2026. március és április', 'Kisállatlista, adatlap, szerkesztés és törlés, menhely adatlap galériával, hibaoldalak'],
          ['2026. május', 'Keresés és szűrés, munkatárskezelés'],
          ['2026. június', 'Üzenetküldés, automatikus kihelyezés, mobilnézet, unit tesztek'],
          ['2026. október', 'Saját szerver és domain, napi demo-visszaállítás, e2e tesztek az éles oldal ellen, kódtisztítás'],
        ] as [$period, $work])
          <div class="py-5 flex flex-col gap-1 sm:flex-row sm:gap-6">
            <span class="sm:w-56 sm:shrink-0 text-sm font-medium text-white">{{ $period }}</span>
            <span class="text-sm text-white/50 leading-relaxed">{{ $work }}</span>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <section class="bg-white py-20 md:py-28" data-test-id="about-improvements">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400 shrink-0">Továbbfejlesztési lehetőségek</span>
        <div class="flex-1 h-px bg-neutral-200"></div>
      </div>

      <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 max-w-4xl">
        @foreach([
          'Kedvelések, hozzászólások és örökbefogadási folyamat',
          'Több menhely kezelése egy tulajdonossal',
          'Adminisztrátori szerepkör',
          'A kisállat nemének megjelenítése az adatlapon',
          'Válasz az üzenetekre az oldalon belül, értesítés új üzenetről',
          'Kedvencek listája és értesítés státuszváltozásról',
        ] as $item)
          <li class="flex gap-3 text-sm leading-relaxed text-neutral-600">
            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-neutral-300"></span>
            {{ $item }}
          </li>
        @endforeach
      </ul>

    </div>
  </section>

  <section class="bg-neutral-900 py-20 md:py-24 text-white" data-test-id="about-project">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">A projektről</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <div class="max-w-2xl">
        <p class="text-white/60 text-base leading-relaxed">
          Ez az oldal egy egyetemi szakdolgozat részeként készült 2024 decembere és 2026 októbere
          között. A szakdolgozat a rendszer teljes életciklusát mutatja be: adatmodell, szerveroldali
          logika, reszponzív felület, szerepköralapú jogosultságkezelés, képfeltöltés, automatizált
          tesztelés és éles üzemeltetés automatikus kihelyezéssel.
        </p>
      </div>

    </div>
  </section>

</x-guest-layout>
