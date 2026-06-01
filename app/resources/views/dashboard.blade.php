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
        secondary-href="route('pets.index')"
        secondary-label="Kisállatok megtekintése"
      />

      <div class="mt-12 grid grid-cols-1 gap-6">

        @php
          $authUser      = auth()->user();
          $isOwner       = $authUser && $authUser->type === 'Shelterowner';
          $isWorker      = $authUser && $authUser->type === 'Shelterworker';
          $workerShelter = $authUser->worksAt;
        @endphp

        @if($isOwner)
          @if(auth()->user()->shelter)
            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelter.edit', auth()->user()->shelter)"
              title="Menhely módosítása"
              description="Adatlap, leírás, képek, elérhetőségek frissítése."
            >
              <x-slot:icon>
                <x-icon name="pencil" class="h-7 w-7" />
              </x-slot:icon>
            </x-ui.action-card>

            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelters.show', auth()->user()->shelter)"
              title="Saját menhely megtekintése"
              description="Menhelyed publikus adatlapja, elérhetőségek, képek."
            >
              <x-slot:icon>
                <x-icon name="home" class="h-7 w-7" />
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
                <x-icon name="paw" class="h-7 w-7" />
              </x-slot:icon>
            </x-ui.action-card>
          @endif

          @php
            if (auth()->user()->shelter) {
                $staffingHref = route('shelter.staffing.index', auth()->user()->shelter);
            } else {
                $staffingHref = '#';
            }
          @endphp
          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="$staffingHref"
            title="Munkatárs felvétele"
            description="Hívj meg önkénteseket vagy adj hozzá kezelői jogosultságot."
          >
            <x-slot:icon>
              <x-icon name="user" class="h-7 w-7" />
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('settings.index')"
            title="Fiókbeállítások"
            description="Profil- és jelszókezelés, értesítések, biztonság."
          >
            <x-slot:icon>
              <x-icon name="settings" class="h-7 w-7" />
            </x-slot:icon>
          </x-ui.action-card>

        @elseif($isWorker)

          @if(! $workerShelter)
            <div class="w-full rounded-2xl bg-amber-50 px-8 py-8 ring-1 ring-amber-200">
              <div class="flex items-start gap-4">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-amber-100">
                  <x-icon name="user" class="h-6 w-6 opacity-60 text-amber-700" />
                </div>
                <div>
                  <p class="font-semibold text-amber-900">Még nem vagy hozzárendelve menhelyhez</p>
                  <p class="mt-1 text-sm text-amber-700">
                    Kérd meg a menhely tulajdonosát, hogy adjon hozzá munkatársként. Az e-mail címed: <strong>{{ $authUser->email }}</strong>
                  </p>
                </div>
              </div>
            </div>
          @else
            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('shelters.show', $workerShelter)"
              title="Menhelyem megtekintése"
              description="{{ $workerShelter->name }} – publikus adatlap."
            >
              <x-slot:icon>
                <x-icon name="home" class="h-7 w-7" />
              </x-slot:icon>
            </x-ui.action-card>

            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('pets.create')"
              title="Új kisállat felvétele"
              description="Adj hozzá új kisállatot a menhelyhez."
            >
              <x-slot:icon>
                <x-icon name="paw" class="h-7 w-7" />
              </x-slot:icon>
            </x-ui.action-card>

            <x-ui.action-card
              class="w-full p-8 md:p-10"
              :href="route('pets.update.index')"
              title="Saját kisállatok kezelése"
              description="A te nevedre felvett kisállatok szerkesztése, státuszkezelés."
            >
              <x-slot:icon>
                <x-icon name="pencil" class="h-7 w-7" />
              </x-slot:icon>
            </x-ui.action-card>

            <form
              method="POST"
              action="{{ route('staffing.leave') }}"
              x-data
              x-on:submit.prevent="confirm('Biztosan elhagyod a(z) {{ $workerShelter->name }} menhelyet?') && $el.submit()"
            >
              @csrf
              @method('DELETE')
              <button type="submit"
                class="group w-full block rounded-2xl border border-red-200 bg-white p-8 md:p-10 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition text-left">
                <div class="flex gap-4">
                  <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <x-icon name="exit" class="h-7 w-7" />
                  </div>
                  <div class="flex-1">
                    <h3 class="text-xl md:text-2xl font-semibold text-neutral-900 group-hover:underline">Menhely elhagyása</h3>
                    <p class="mt-1 text-neutral-600">Kilépés a(z) {{ $workerShelter->name }} munkatársai közül.</p>
                  </div>
                  <div class="self-center">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-red-200 text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                      <x-icon name="arrow-right" class="h-4 w-4" />
                    </span>
                  </div>
                </div>
              </button>
            </form>
          @endif

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('settings.index')"
            title="Fiókbeállítások"
            description="Profil- és jelszókezelés, biztonsági beállítások."
          >
            <x-slot:icon>
              <x-icon name="settings" class="h-7 w-7" />
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
              <x-icon name="home" class="h-7 w-7" />
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            href="#"
            title="Kisállatok"
            description="Böngészd a gazdira váró kisállatokat és szűrj típussal, várossal."
          >
            <x-slot:icon>
              <x-icon name="paw" class="h-7 w-7" />
            </x-slot:icon>
          </x-ui.action-card>

          <x-ui.action-card
            class="w-full p-8 md:p-10"
            :href="route('settings.index')"
            title="Fiókbeállítások"
            description="Profil- és jelszókezelés, értesítések, biztonsági beállítások."
          >
            <x-slot:icon>
              <x-icon name="user" class="h-7 w-7" />
            </x-slot:icon>
          </x-ui.action-card>
        @endif
      </div>
    </x-ui.container>
  </div>
</x-app-layout>
