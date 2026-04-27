<x-app-layout>
  <x-ui.breadcrumbs/>
  <div class="bg-gradient-to-b from-neutral-50 via-white to-white">
    <x-ui.container class="py-10">

      <x-ui.hero
        title="Kezdjük el a jót: segítsünk új otthont találni"
        description="Böngéssz a menhelyek és kisállatok között, indíts új menhelyet, vagy kezeld a meglévődet – minden egy helyen."
        :image="asset('images/hero-shelter.svg')"
        :primary-href="route('shelters.index')"
        primary-label="Menhelyek böngészése"
        secondary-href="#"
        secondary-label="Kisállatok megtekintése"
      />

      <div class="mt-12 grid grid-cols-1 gap-6">

        @if(auth()->check() && auth()->user()->type === 'Shelterowner')
          @if(auth()->user()->shelter)
            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelter.edit', auth()->user()->shelter)"
              title="Menhely módosítása"
              description="Adatlap, leírás, képek, elérhetőségek frissítése."
            >
              <x-slot:icon>
                <img src="{{ asset('images/pencil.svg') }}" alt="" class="h-7 w-7">
              </x-slot:icon>
            </x-ui.action-card>

            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelters.show', auth()->user()->shelter)"
              title="Saját menhely megtekintése"
              description="Menhelyed publikus adatlapja, elérhetőségek, képek."
            >
              <x-slot:icon>
                <img src="{{ asset('images/home.svg') }}" alt="" class="h-7 w-7">
              </x-slot:icon>
            </x-ui.action-card>
          @else
            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelter.create')"
              title="Menhely létrehozása"
              description="Állítsd be a menhelyed nevét, leírását és tölts fel képeket."
            >
              <x-slot:icon>
                <img src="{{ asset('images/pet.svg') }}" alt="" class="h-7 w-7">
              </x-slot:icon>
            </x-ui.action-card>
          @endif

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            href="#"
            title="Munkatárs felvétele"
            description="Hívj meg önkénteseket vagy adj hozzá kezelői jogosultságot."
          >
            <x-slot:icon>
              <img src="{{ asset('images/profile.svg') }}" alt="" class="h-7 w-7">
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('settings.index')"
            title="Fiókbeállítások"
            description="Profil- és jelszókezelés, értesítések, biztonság."
          >
            <x-slot:icon>
              <img src="{{ asset('images/settings.svg') }}" alt="" class="h-7 w-7">
            </x-slot:icon>
          </x-ui.action-card>

        @else
          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('shelters.index')"
            title="Menhelyek"
            description="Fedezd fel a menhelyeket, nézd meg a részleteket, vedd fel a kapcsolatot."
          >
            <x-slot:icon>
              <img src="{{ asset('images/home.svg') }}" alt="" class="h-7 w-7">
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            href="#"
            title="Kisállatok"
            description="Böngészd a gazdira váró kisállatokat és szűrj típussal, várossal."
          >
            <x-slot:icon>
              <img src="{{ asset('images/pet.svg') }}" alt="" class="h-7 w-7">
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('settings.index')"
            title="Fiókbeállítások"
            description="Profil- és jelszókezelés, értesítések, biztonsági beállítások."
          >
            <x-slot:icon>
              <img src="{{ asset('images/profile.svg') }}" alt="" class="h-7 w-7">
            </x-slot:icon>
          </x-ui.action-card>
        @endif
      </div>
    </x-ui.container>
  </div>
</x-app-layout>
