<x-navbar.base x-data="{ open: null }">
  @php
    $isOwner = auth()->check() && auth()->user()->type === 'Shelterowner';
    $ownShelter = $isOwner ? auth()->user()->shelter : null;
  @endphp

  <div class="col-span-3 flex items-center">
    <x-navbar.logo :href="auth()->check() ? route('dashboard') : route('home')" />
  </div>

  @if($isOwner)
    <div class="col-start-5 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'shelter'" @focusin="open = 'shelter'">
      <x-navbar.menu-item
        label="Menhelyem"
        :href="$ownShelter ? route('shelters.show', $ownShelter) : '#'"
      />
    </div>

    <div class="col-start-7 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'pets'" @focusin="open = 'pets'">
      <x-navbar.menu-item
        label="Kisállatok"
        :href="route('pets.index')"
      />
    </div>

    <div class="col-start-9 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'staff'" @focusin="open = 'staff'">
      <x-navbar.menu-item
        label="Munkatársak"
        :href="url('/staff')"
      />
    </div>

    <div class="col-start-11 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item
        label="Fiók"
        :href="route('settings.index')"
      />
    </div>
  @else
    <div class="col-start-7 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'shelters-public'" @focusin="open = 'shelters-public'">
      <x-navbar.menu-item
        label="Menhelyek"
        :href="route('shelters.index')"
      />
    </div>

    <div class="col-start-9 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'pets-public'" @focusin="open = 'pets-public'">
      <x-navbar.menu-item
        label="Kisállatok"
        :href="route('pets.index')"
      />
    </div>

    <div class="col-start-11 col-span-2 hidden md:flex items-center"
         @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item
        label="Fiók"
        href="#"
      />
    </div>
  @endif

  <div class="md:hidden col-start-12 col-span-1 flex items-center justify-end">
    <button
      type="button"
      class="text-sm font-medium transition-colors duration-200"
      :class="open !== null ? 'text-white/70 hover:text-white' : 'text-neutral-700 hover:text-neutral-900'"
    >
      Menu
    </button>
  </div>

  <x-slot:dropdown>
    <div
      x-show="open !== null"
      x-transition:enter="transition-all ease-out duration-300"
      x-transition:enter-start="[clip-path:inset(0_0_100%_0)] opacity-0"
      x-transition:enter-end="[clip-path:inset(0_0_0%_0)] opacity-100"
      x-transition:leave="transition-all ease-in duration-200"
      x-transition:leave-start="[clip-path:inset(0_0_0%_0)] opacity-100"
      x-transition:leave-end="[clip-path:inset(0_0_100%_0)] opacity-0"
      class="absolute inset-x-0 top-full bg-[#2b2b2b] text-white shadow-xl ring-1 ring-black/5"
      style="display: none;"
    >
      <div class="grid grid-cols-12 gap-8 px-6 py-6 lg:px-8">
        @if($isOwner)
          <div class="col-start-5 col-span-2" x-show="open === 'shelter'">
            <x-navbar.section title="Menhelyem">
              <x-navbar.link :href="$ownShelter ? route('shelters.show', $ownShelter) : '#'">Publikus oldal</x-navbar.link>
              <x-navbar.link :href="$ownShelter ? route('shelter.edit', $ownShelter) : '#'">Szerkesztés</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-7 col-span-2" x-show="open === 'pets'">
            <x-navbar.section title="Kisállatok">
              <x-navbar.link :href="route('pets.create')">Új kisállat</x-navbar.link>
              <x-navbar.link :href="route('pets.index')">Összes kisállat</x-navbar.link>
              <x-navbar.link :href="route('pets.update.index')">Saját kisállatok</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-9 col-span-2" x-show="open === 'staff'">
            <x-navbar.section title="Munkatársak">
              <x-navbar.link :href="url('/staff')">Kezelés</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-11 col-span-2" x-show="open === 'account'">
            <x-navbar.section title="Fiók">
              <x-navbar.account />
            </x-navbar.section>
          </div>
        @else
          <div class="col-start-7 col-span-2" x-show="open === 'shelters-public'">
            <x-navbar.section title="Menhelyek">
              <x-navbar.link :href="route('shelters.index')">Összes menhely</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-9 col-span-2" x-show="open === 'pets-public'">
            <x-navbar.section title="Kisállatok">
              <x-navbar.link :href="route('pets.index')">Összes kisállat</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-11 col-span-2" x-show="open === 'account'">
            <x-navbar.section title="Fiók">
              <x-navbar.account />
            </x-navbar.section>
          </div>
        @endif
      </div>
    </div>
  </x-slot:dropdown>
</x-navbar.base>
