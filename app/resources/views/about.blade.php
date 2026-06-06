<x-guest-layout>
  @section('title', 'Tudj meg többet')

  <section class="bg-neutral-900 text-white">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-20 md:py-28">
      <span class="text-[11px] uppercase tracking-[0.2em] text-white/40">Szakdolgozat projekt</span>
      <h1 class="mt-3 text-4xl md:text-6xl font-semibold tracking-tight leading-tight max-w-3xl">
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
           class="inline-flex items-center gap-2 rounded-full bg-white text-neutral-900 px-6 py-3 text-sm font-semibold hover:bg-neutral-100 transition">
          Kisállatok böngészése
        </a>
        <a href="{{ route('shelters.index') }}"
           class="inline-flex items-center gap-2 rounded-full bg-white/10 text-white px-6 py-3 text-sm font-semibold ring-1 ring-white/20 hover:bg-white/15 transition">
          Menhelyek megtekintése
        </a>
      </div>
    </div>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white">
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

  <section class="bg-white py-20 md:py-28">
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

  <section class="bg-neutral-900 py-20 md:py-24 text-white">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">A projektről</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <div class="max-w-2xl">
        <p class="text-white/60 text-base leading-relaxed">
          Ez az oldal egy egyetemi szakdolgozat részeként készült, Laravel 11, Livewire 3
          és Tailwind CSS technológiákkal. Megvalósított területek: hitelesítés,
          szerepköralapú hozzáférés-szabályozás, fájlkezelés, valós idejű komponensek
          és reszponzív felhasználói felület.
        </p>
      </div>

    </div>
  </section>

</x-guest-layout>
