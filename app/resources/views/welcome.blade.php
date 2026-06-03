<x-guest-layout>
  @section('title', 'Tappmancs Kezdőlap')

  <section class="relative overflow-hidden bg-neutral-900 text-white">
    <x-ui.home.container class="relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center py-16 md:py-24">
        <div>
          <x-ui.home.h1 class="text-white">Tappmancs</x-ui.home.h1>

          <x-ui.home.h2 class="text-white/90">Fogadj örökbe kisállatot vagy indíts el a saját menhelyed!</x-ui.home.h2>

          <x-ui.home.lead class="text-white/70">
            Csatlakozz egy olyan közösséghez, amely elkötelezett az állatok életének jobbá tétele iránt.
            Ismerd meg, hogyan segíthetsz, és kezdj hozzá most!
          </x-ui.home.lead>

          <div class="flex flex-col items-start">
            <div class="w-full transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-2">
              <x-ui.home.cta-button :href="route('shelters.index')" label="Tudj meg többet!" />
            </div>

            <div class="flex gap-4 md:gap-6 mt-8 md:mt-12">
              <div class="transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-2">
                <x-ui.home.stat :target="$petCount" label="Kisállatok" :href="route('pets.index')" />
              </div>
              <div class="transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-2">
                <x-ui.home.stat :target="$shelterCount" label="Menhely" :href="route('shelters.index')" />
              </div>
            </div>
          </div>
        </div>

          <div class="hidden md:block">
            <x-ui.home.image-frame
              :src="asset('images/collar-dog.svg')"
              alt="Örökbefogadás"
            />
          </div>
      </div>
    </x-ui.home.container>
  </section>

  <section class="bg-neutral-900 border-t border-white/[0.07] py-20 md:py-28 text-white">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="mb-14 flex items-center gap-5">
        <span class="text-[11px] uppercase tracking-[0.2em] text-white/40 shrink-0">Hogyan működik?</span>
        <div class="flex-1 h-px bg-white/10"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3">
        @foreach([
          ['01', 'Böngéssz', 'Keresd meg az elérhető kisállatokat és menhelyeket a platformon. Szűrj faj, kor és hely szerint.'],
          ['02', 'Vedd fel a kapcsolatot', 'Érdeklődjél a kisállatról közvetlenül a menhelynél az oldal beépített kapcsolati rendszerén keresztül.'],
          ['03', 'Fogadj örökbe!', 'Teljesítsd az örökbefogadási folyamatot, és vidd haza új barátod – egy életre.'],
        ] as $i => [$num, $title, $desc])
        <div
          class="group relative py-10 md:py-0 md:px-10 first:md:pl-0 last:md:pr-0
                 border-t border-white/10 md:border-t-0 first:border-t-0
                 md:border-l first:md:border-l-0"
        >
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

  @if($featuredPets->isNotEmpty())
  <section class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
        <div>
          <span class="text-[11px] uppercase tracking-[0.2em] text-neutral-400">Örökbefogadásra vár</span>
          <h2 class="mt-2 text-4xl md:text-5xl font-semibold text-neutral-900 leading-tight">
            Legújabb kisállatok
          </h2>
        </div>
        <a
          href="{{ route('pets.index') }}"
          class="mt-5 md:mt-0 inline-flex items-center gap-2.5 text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors duration-200 group"
        >
          Összes kisállat
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full border border-neutral-300
                       group-hover:bg-neutral-900 group-hover:border-neutral-900 transition-all duration-300">
            <x-icon name="chevron-right" class="w-3 h-3 text-neutral-500 group-hover:text-white transition-colors duration-300" />
          </span>
        </a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredPets as $pet)
        <div>
          <x-pet.card
            :href="route('pets.show', $pet->uuid)"
            :name="$pet->name"
            :description="$pet->excerpt"
            :badge="$pet->status_label"
            :image="$pet->first_image_path"
            :shelterName="$pet->shelter?->name"
            :meta="['Fajta' => $pet->breed?->name, 'Kor' => $pet->age_label]"
          />
        </div>
        @endforeach
      </div>

    </div>
  </section>
  @endif

  @if($featuredShelters->isNotEmpty())
  <section class="bg-neutral-900 py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

      <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-12">
        <div>
          <span class="text-[11px] uppercase tracking-[0.2em] text-white/40">Csatlakozott menhelyek</span>
          <h2 class="mt-2 text-4xl md:text-5xl font-semibold text-white leading-tight">
            Menhelyeink
          </h2>
        </div>
        <a
          href="{{ route('shelters.index') }}"
          class="mt-5 md:mt-0 inline-flex items-center gap-2.5 text-sm font-medium text-white/60 hover:text-white transition-colors duration-200 group"
        >
          Összes menhely
          <span class="inline-flex items-center justify-center w-7 h-7 rounded-full border border-white/20
                       group-hover:bg-white group-hover:border-white transition-all duration-300">
            <x-icon name="chevron-right" class="w-3 h-3 text-white/60 group-hover:text-neutral-900 transition-colors duration-300" />
          </span>
        </a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredShelters as $i => $shelter)
        <div>
          <x-shelter.card
            :href="route('shelters.show', $shelter->uuid)"
            :name="$shelter->name"
            :description="$shelter->description"
            :badge="$shelter->pets_count.' kisállat'"
            :image="$shelter->cover_image"
          />
        </div>
        @endforeach
      </div>

    </div>
  </section>
  @endif

</x-guest-layout>
