<x-navbar.base x-data="{ open: null }">
  <div class="col-span-3 flex">
    <x-navbar.logo :href="auth()->check() ? route('dashboard') : route('home')" />
  </div>

  @php
    $isOwner = auth()->check() && auth()->user()->type === 'Shelterowner';
    $ownShelter = $isOwner ? auth()->user()->shelter : null;
  @endphp

  @if($isOwner)
    <div class="col-start-5 col-span-2 hidden md:flex justify-start" @mouseenter="open = 'shelter'" @focusin="open = 'shelter'">
      <x-navbar.menu-item
        label="Menhelyem"
        :href="$ownShelter ? route('shelters.show', $ownShelter) : '#'"
      />
    </div>

    <div class="col-start-9 col-span-2 hidden md:flex justify-start" @mouseenter="open = 'pets'" @focusin="open = 'pets'">
      <x-navbar.menu-item
        label="Kisállatok"
        :href="route('pets.index')"
      />
    </div>

    <div class="col-start-11 col-span-2 hidden md:flex" @mouseenter="open = 'staff'" @focusin="open = 'staff'">
      <x-navbar.menu-item
        label="Munkatársak"
        :href="url('/staff')"
      />
    </div>
  @else
    <div class="col-start-8 col-span-2 hidden md:flex justify-start" @mouseenter="open = 'shelters-public'" @focusin="open = 'shelters-public'">
      <x-navbar.menu-item label="Menhelyek" :href="route('shelters.index')" />
    </div>

    <div class="col-start-10 col-span-2 hidden md:flex justify-start" @mouseenter="open = 'pets-public'" @focusin="open = 'pets-public'">
      <x-navbar.menu-item label="Kisállatok" :href="route('pets.index')" />
    </div>

    <div class="col-start-12 col-span-1 hidden md:flex justify-start" @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item label="Fiók" href="#" />
    </div>
  @endif

  <div class="md:hidden col-start-12 col-span-1 ml-3 text-sm">
    Menu
  </div>

  <x-slot:dropdown>
    <div
      x-show="open !== null"
      x-transition
      @mouseenter="open = open"
      @mouseleave="open = null"
      class="absolute inset-x-0 top-full w-full bg-[#2b2b2b] text-white shadow-xl ring-1 ring-black/5"
      style="display: none;"
    >
      <div class="grid grid-cols-12 gap-8 px-6 py-6 lg:px-8">
        @if($isOwner)
          <div class="col-start-5 col-span-2 flex justify-start" x-show="open === 'shelter'" x-transition>
            <x-navbar.section title="Menhelyem">
              <x-navbar.link
                :href="$ownShelter ? route('shelters.show', $ownShelter) : '#'"
                class="text-white hover:text-black hover:font-bold"
              >
                Publikus oldal
              </x-navbar.link>

              <x-navbar.link
                :href="$ownShelter ? route('shelter.edit', $ownShelter) : '#'"
                class="text-white hover:text-black hover:font-bold"
              >
                Szerkesztés
              </x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-9 col-span-2 flex justify-start" x-show="open === 'pets'" x-transition>
            <x-navbar.section title="Kisállatok">
              <x-navbar.link :href="route('pets.create')" class="text-white hover:text-black hover:font-bold">
                Új kisállat
              </x-navbar.link>

              <x-navbar.link :href="route('pets.index')" class="text-white hover:text-black hover:font-bold">
                Összes kisállat
              </x-navbar.link>

              <x-navbar.link :href="route('pets.update.index')" class="text-white hover:text-black hover:font-bold">
                Saját kisállatok
              </x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-11 col-span-2" x-show="open === 'staff'" x-transition>
            <x-navbar.section title="Munkatársak">
              <x-navbar.link :href="url('/staff')" class="text-white hover:text-black hover:font-bold">
                Kezelés
              </x-navbar.link>

              <x-navbar.account />
            </x-navbar.section>
          </div>
        @else
          <div class="col-start-8 col-span-2 flex justify-start" x-show="open === 'shelters-public'" x-transition>
            <x-navbar.section title="Menhelyek">
              <x-navbar.link :href="route('shelters.index')" class="text-white hover:text-black hover:font-bold">
                Összes menhely
              </x-navbar.link>

              <x-navbar.link href="#" class="text-white hover:text-black hover:font-bold">
                Debrecen
              </x-navbar.link>

              <x-navbar.link href="#" class="text-white hover:text-black hover:font-bold">
                Budapest
              </x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-10 col-span-2 flex justify-start" x-show="open === 'pets-public'" x-transition>
            <x-navbar.section title="Kisállatok">
              <x-navbar.link :href="route('pets.index')" class="text-white hover:text-black hover:font-bold">
                Összes
              </x-navbar.link>

              <x-navbar.link :href="route('pets.index')" class="text-white hover:text-black hover:font-bold">
                Kutyák
              </x-navbar.link>

              <x-navbar.link :href="route('pets.index')" class="text-white hover:text-black hover:font-bold">
                Macskák
              </x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-12 col-span-1 flex justify-start" x-show="open === 'account'" x-transition>
            <x-navbar.section title="Fiók">
              <x-navbar.account />
            </x-navbar.section>
          </div>
        @endif
      </div>
    </div>
  </x-slot:dropdown>
</x-navbar.base>