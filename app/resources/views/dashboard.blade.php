<x-app-layout>
  <x-ui.breadcrumbs/>

  @php
    $authUser      = auth()->user();
    $isOwner       = $authUser && $authUser->type === 'Shelterowner';
    $isWorker      = $authUser && $authUser->type === 'Shelterworker';
    $workerShelter = $authUser->worksAt;

    $roleLabel = match($authUser->type) {
      'Shelterowner'  => 'Menhely tulajdonos',
      'Shelterworker' => 'Munkatárs',
      default         => 'Felhasználó',
    };
    $roleColor = match($authUser->type) {
      'Shelterowner'  => 'bg-blue-100 text-blue-700',
      'Shelterworker' => 'bg-emerald-100 text-emerald-700',
      default         => 'bg-neutral-100 text-neutral-600',
    };
  @endphp

  <x-ui.hero
    title="Kezdjük el a jót: segítsünk új otthont találni"
    description="Böngéssz a menhelyek és kisállatok között, indíts új menhelyet, vagy kezeld a meglévődet – minden egy helyen."
    :image="asset('images/hero-shelter.svg')"
    :primary-href="route('shelters.index')"
    primary-label="Menhelyek böngészése"
    :secondary-href="route('pets.index')"
    secondary-label="Kisállatok megtekintése"
  />

  <div class="mt-10 flex flex-wrap items-center gap-3 mb-6">
    <h2 class="text-lg font-semibold text-neutral-900">{{ $authUser->name }}</h2>
    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $roleColor }}">
      {{ $roleLabel }}
    </span>
  </div>

  <div class="grid grid-cols-1 gap-3">

    @if($isOwner)

      @if($authUser->shelter)
        <x-ui.action-card
          :href="route('shelters.show', $authUser->shelter)"
          title="Saját menhely"
          description="{{ $authUser->shelter->name }} – publikus adatlap."
          icon-class="bg-blue-50 text-blue-600"
        >
          <x-slot:icon><x-icon name="home" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('shelter.edit', $authUser->shelter)"
          title="Menhely szerkesztése"
          description="Adatlap, leírás, képek, elérhetőségek frissítése."
          icon-class="bg-blue-50 text-blue-600"
        >
          <x-slot:icon><x-icon name="pencil" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('shelter.staffing.index', $authUser->shelter)"
          title="Munkatársak kezelése"
          description="Hívj meg önkénteseket, adj hozzá vagy távolíts el munkatársat."
          icon-class="bg-purple-50 text-purple-600"
        >
          <x-slot:icon><x-icon name="user" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('pets.create')"
          title="Új kisállat felvétele"
          description="Adj hozzá kisállatot a menhelyedhez."
          icon-class="bg-emerald-50 text-emerald-600"
        >
          <x-slot:icon><x-icon name="paw" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('pets.update.index')"
          title="Kisállatok kezelése"
          description="Feltöltött kisállatok szerkesztése, státuszkezelés."
          icon-class="bg-emerald-50 text-emerald-600"
        >
          <x-slot:icon><x-icon name="pencil" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>
      @else
        <x-ui.action-card
          :href="route('shelter.create')"
          title="Menhely létrehozása"
          description="Állítsd be a menhelyed nevét, leírását és tölts fel képeket."
          icon-class="bg-blue-50 text-blue-600"
        >
          <x-slot:icon><x-icon name="paw" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>
      @endif

      <x-ui.action-card
        :href="route('settings.index')"
        title="Fiókbeállítások"
        description="Profil- és jelszókezelés, biztonsági beállítások."
      >
        <x-slot:icon><x-icon name="settings" class="h-6 w-6" /></x-slot:icon>
      </x-ui.action-card>

    @elseif($isWorker)

      @if(! $workerShelter)
        <div class="rounded-2xl bg-amber-50 px-6 py-6 ring-1 ring-amber-200">
          <div class="flex items-start gap-4">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-amber-100">
              <x-icon name="user" class="h-5 w-5 text-amber-700" />
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
          :href="route('shelters.show', $workerShelter)"
          title="Menhelyem"
          description="{{ $workerShelter->name }} – publikus adatlap."
          icon-class="bg-blue-50 text-blue-600"
        >
          <x-slot:icon><x-icon name="home" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('pets.create')"
          title="Új kisállat felvétele"
          description="Adj hozzá új kisállatot a menhelyhez."
          icon-class="bg-emerald-50 text-emerald-600"
        >
          <x-slot:icon><x-icon name="paw" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('pets.update.index')"
          title="Kisállataim kezelése"
          description="A te nevedre felvett kisállatok szerkesztése, státuszkezelés."
          icon-class="bg-emerald-50 text-emerald-600"
        >
          <x-slot:icon><x-icon name="pencil" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <x-ui.action-card
          :href="route('settings.index')"
          title="Fiókbeállítások"
          description="Profil- és jelszókezelés, biztonsági beállítások."
        >
          <x-slot:icon><x-icon name="settings" class="h-6 w-6" /></x-slot:icon>
        </x-ui.action-card>

        <form
          method="POST"
          action="{{ route('staffing.leave') }}"
          x-data
          x-on:submit.prevent="confirm({{ Js::from('Biztosan elhagyod a(z) ' . $workerShelter->name . ' menhelyet?') }}) && $el.submit()"
        >
          @csrf
          @method('DELETE')
          <button type="submit"
            class="group w-full block rounded-2xl border border-red-200 bg-white p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition text-left">
            <div class="flex gap-4">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-500">
                <x-icon name="exit" class="h-6 w-6" />
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-base font-semibold text-neutral-900 group-hover:underline">Menhely elhagyása</h3>
                <p class="mt-0.5 text-sm text-neutral-500">Kilépés a(z) {{ $workerShelter->name }} munkatársai közül.</p>
              </div>
              <div class="self-center shrink-0">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-red-200 text-red-500 group-hover:bg-red-500 group-hover:border-red-500 group-hover:text-white transition">
                  <x-icon name="arrow-right" class="h-4 w-4" />
                </span>
              </div>
            </div>
          </button>
        </form>
      @endif

    @else

      <x-ui.action-card
        :href="route('shelters.index')"
        title="Menhelyek"
        description="Fedezd fel a menhelyeket, vedd fel a kapcsolatot."
        icon-class="bg-blue-50 text-blue-600"
      >
        <x-slot:icon><x-icon name="home" class="h-6 w-6" /></x-slot:icon>
      </x-ui.action-card>

      <x-ui.action-card
        :href="route('pets.index')"
        title="Kisállatok"
        description="Böngészd a gazdira váró kisállatokat, szűrj típussal, várossal."
        icon-class="bg-emerald-50 text-emerald-600"
      >
        <x-slot:icon><x-icon name="paw" class="h-6 w-6" /></x-slot:icon>
      </x-ui.action-card>

      <x-ui.action-card
        :href="route('settings.index')"
        title="Fiókbeállítások"
        description="Profil- és jelszókezelés, biztonsági beállítások."
      >
        <x-slot:icon><x-icon name="settings" class="h-6 w-6" /></x-slot:icon>
      </x-ui.action-card>

    @endif

  </div>
</x-app-layout>
