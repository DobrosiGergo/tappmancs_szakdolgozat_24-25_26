@php
    $dark          = request()->routeIs('home');
    $user          = auth()->user();
    $isOwner       = $user && $user->type === 'Shelterowner';
    $isWorker      = $user && $user->type === 'Shelterworker';
    $ownShelter    = $user?->shelter;
    $workerShelter = $user?->worksAt;
    $unreadCount = 0;
    if ($user) {
        $unreadCount = $user->unreadNotifications()->count();
    }

    if (auth()->check()) {
        $logoHref = route('dashboard');
    } else {
        $logoHref = route('home');
    }

    if ($dark) {
        $bellClass = 'text-white/60 hover:text-white hover:bg-white/10';
    } else {
        $bellClass = 'text-neutral-500 hover:text-neutral-900 hover:bg-neutral-100';
    }
@endphp

<x-navbar.base :dark="$dark" x-data="{ open: null }">

  <div class="col-span-3 flex items-center">
    <x-navbar.logo :dark="$dark" :href="$logoHref" />
  </div>

  @if($isOwner)

    <div class="col-start-5 col-span-2 hidden lg:flex items-center"
         @mouseenter="open = 'shelter'" @focusin="open = 'shelter'">
      <x-navbar.menu-item :dark="$dark" label="Menhelyem" :href="route('shelters.show', $ownShelter)" />
    </div>

    <div class="col-start-7 col-span-2 hidden lg:flex items-center"
         @mouseenter="open = 'pets'" @focusin="open = 'pets'">
      <x-navbar.menu-item :dark="$dark" label="Kisállatok" :href="route('pets.index')" />
    </div>

    <div class="col-start-9 col-span-2 hidden lg:flex items-center"
         @mouseenter="open = 'staff'" @focusin="open = 'staff'">
      <x-navbar.menu-item :dark="$dark" label="Munkatársak" :href="route('shelter.staffing.index', $ownShelter)" />
    </div>

    <div class="col-start-11 col-span-1 hidden lg:flex items-center"
         @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item :dark="$dark" label="Fiók" :href="route('settings.index')" />
    </div>

  @elseif($isWorker)

    @if($workerShelter)
      <div class="col-start-7 col-span-2 hidden lg:flex items-center"
           @mouseenter="open = 'worker-shelter'" @focusin="open = 'worker-shelter'">
        <x-navbar.menu-item :dark="$dark" label="Menhely" :href="route('shelters.show', $workerShelter)" />
      </div>

      <div class="col-start-9 col-span-2 hidden lg:flex items-center"
           @mouseenter="open = 'pets'" @focusin="open = 'pets'">
        <x-navbar.menu-item :dark="$dark" label="Kisállatok" :href="route('pets.index')" />
      </div>
    @else
      <div class="col-start-7 col-span-4 hidden lg:flex items-center">
        <span class="inline-flex items-center gap-2 rounded-full bg-amber-400/15 px-4 py-1.5 text-xs font-semibold text-amber-400 ring-1 ring-amber-400/30">
          <span class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></span>
          Még nem vagy hozzárendelve menhelyhez
        </span>
      </div>
    @endif

    <div class="col-start-11 col-span-1 hidden lg:flex items-center"
         @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item :dark="$dark" label="Fiók" :href="route('settings.index')" />
    </div>

  @else

    <div class="col-start-7 col-span-2 hidden lg:flex items-center"
         @mouseenter="open = 'shelters-public'" @focusin="open = 'shelters-public'">
      <x-navbar.menu-item :dark="$dark" label="Menhelyek" :href="route('shelters.index')" />
    </div>

    <div class="col-start-9 col-span-2 hidden lg:flex items-center"
         @mouseenter="open = 'pets-public'" @focusin="open = 'pets-public'">
      <x-navbar.menu-item :dark="$dark" label="Kisállatok" :href="route('pets.index')" />
    </div>

    <div class="col-start-11 col-span-1 hidden lg:flex items-center"
         @mouseenter="open = 'account'" @focusin="open = 'account'">
      <x-navbar.menu-item :dark="$dark" label="Fiók" :href="route('login')" />
    </div>

  @endif

  @if($user)
    <div class="col-start-12 col-span-1 hidden md:flex items-center justify-center">
      <a href="{{ route('notifications.index') }}"
         class="relative flex h-8 w-8 items-center justify-center rounded-full transition {{ $bellClass }}">
        <x-icon name="bell" class="h-5 w-5" />
        @if($unreadCount > 0)
          <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
            {{ $unreadCount }}
          </span>
        @endif
      </a>
    </div>
  @endif

  <div class="lg:hidden col-start-12 col-span-1 flex items-center justify-end">
    <button type="button"
      @click="if (open === 'mobile') { open = null } else { open = 'mobile' }"
      class="text-sm font-medium transition-colors duration-200"
      :class="{ 'text-white/70 hover:text-white': @json($dark) || open !== null, 'text-neutral-700 hover:text-neutral-900': !@json($dark) && open === null }">
      <span x-show="open !== 'mobile'">Menü</span>
      <span x-show="open === 'mobile'" x-cloak>✕</span>
    </button>
  </div>

  <x-slot:dropdown>

<div
      x-show="open !== null && open !== 'mobile'"
      x-transition:enter="transition-all ease-out duration-300"
      x-transition:enter-start="[clip-path:inset(0_0_100%_0)] opacity-0"
      x-transition:enter-end="[clip-path:inset(0_0_0%_0)] opacity-100"
      x-transition:leave="transition-all ease-in duration-200"
      x-transition:leave-start="[clip-path:inset(0_0_0%_0)] opacity-100"
      x-transition:leave-end="[clip-path:inset(0_0_100%_0)] opacity-0"
      class="absolute inset-x-0 top-full bg-neutral-900 text-white shadow-xl ring-1 ring-black/5"
      style="display: none;"
    >
      <div class="grid grid-cols-12 gap-8 px-6 py-6 lg:px-8">

        @if($isOwner)

          <div class="col-start-5 col-span-2" x-show="open === 'shelter'">
            <x-navbar.section title="Menhelyem">
              <x-navbar.link :href="route('shelters.show', $ownShelter)">Publikus oldal</x-navbar.link>
              <x-navbar.link :href="route('shelter.edit', $ownShelter)">Szerkesztés</x-navbar.link>
              <x-navbar.link :href="route('messages.index')">Üzenetek</x-navbar.link>
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
              <x-navbar.link :href="route('shelter.staffing.index', $ownShelter)">Kezelés</x-navbar.link>
            </x-navbar.section>
          </div>

          <div class="col-start-11 col-span-2" x-show="open === 'account'">
            <x-navbar.section title="Fiók">
              <x-navbar.account />
            </x-navbar.section>
          </div>

        @elseif($isWorker)

          @if($workerShelter)
            <div class="col-start-7 col-span-2" x-show="open === 'worker-shelter'">
              <x-navbar.section title="Menhely">
                <x-navbar.link :href="route('shelters.show', $workerShelter)">Publikus oldal</x-navbar.link>
              </x-navbar.section>
            </div>

            <div class="col-start-9 col-span-2" x-show="open === 'pets'">
              <x-navbar.section title="Kisállatok">
                <x-navbar.link :href="route('pets.create')">Új kisállat</x-navbar.link>
                <x-navbar.link :href="route('pets.index')">Összes kisállat</x-navbar.link>
                <x-navbar.link :href="route('pets.update.index')">Saját kisállatok</x-navbar.link>
              </x-navbar.section>
            </div>
          @endif

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

    <div
      x-show="open === 'mobile'"
      x-transition:enter="transition-all ease-out duration-200"
      x-transition:enter-start="opacity-0 -translate-y-1"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition-all ease-in duration-150"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-1"
      class="lg:hidden absolute inset-x-0 top-full bg-neutral-900 text-white shadow-xl ring-1 ring-black/5"
      x-cloak
      x-data="{ section: null }"
    >
      <div class="px-6 py-2">

        @if($isOwner)

          <div class="border-b border-white/10">
            <button @click="if (section === 'shelter') { section = null } else { section = 'shelter' }"
                    class="flex w-full items-center justify-between py-3.5 text-sm font-medium text-white">
              Menhelyem
              <x-icon name="chevron-right" class="h-4 w-4 text-white/40 transition-transform duration-200" x-bind:class="{ 'rotate-90': section === 'shelter' }" />
            </button>
            <div x-show="section === 'shelter'" x-cloak class="pb-3 pl-3 flex flex-col gap-1">
              <a href="{{ route('shelters.show', $ownShelter) }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Publikus oldal</a>
              <a href="{{ route('shelter.edit', $ownShelter) }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Szerkesztés</a>
              <a href="{{ route('messages.index') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Üzenetek</a>
            </div>
          </div>

          <div class="border-b border-white/10">
            <button @click="if (section === 'pets') { section = null } else { section = 'pets' }"
                    class="flex w-full items-center justify-between py-3.5 text-sm font-medium text-white">
              Kisállatok
              <x-icon name="chevron-right" class="h-4 w-4 text-white/40 transition-transform duration-200" x-bind:class="{ 'rotate-90': section === 'pets' }" />
            </button>
            <div x-show="section === 'pets'" x-cloak class="pb-3 pl-3 flex flex-col gap-1">
              <a href="{{ route('pets.create') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Új kisállat</a>
              <a href="{{ route('pets.index') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Összes kisállat</a>
              <a href="{{ route('pets.update.index') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Saját kisállatok</a>
            </div>
          </div>

          <div class="border-b border-white/10">
            <button @click="if (section === 'staff') { section = null } else { section = 'staff' }"
                    class="flex w-full items-center justify-between py-3.5 text-sm font-medium text-white">
              Munkatársak
              <x-icon name="chevron-right" class="h-4 w-4 text-white/40 transition-transform duration-200" x-bind:class="{ 'rotate-90': section === 'staff' }" />
            </button>
            <div x-show="section === 'staff'" x-cloak class="pb-3 pl-3 flex flex-col gap-1">
              <a href="{{ route('shelter.staffing.index', $ownShelter) }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Kezelés</a>
            </div>
          </div>

        @elseif($isWorker)

          @if($workerShelter)
            <div class="border-b border-white/10">
              <button @click="if (section === 'shelter') { section = null } else { section = 'shelter' }"
                      class="flex w-full items-center justify-between py-3.5 text-sm font-medium text-white">
                Menhely
                <x-icon name="chevron-right" class="h-4 w-4 text-white/40 transition-transform duration-200" x-bind:class="{ 'rotate-90': section === 'shelter' }" />
              </button>
              <div x-show="section === 'shelter'" x-cloak class="pb-3 pl-3 flex flex-col gap-1">
                <a href="{{ route('shelters.show', $workerShelter) }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Publikus oldal</a>
              </div>
            </div>

            <div class="border-b border-white/10">
              <button @click="if (section === 'pets') { section = null } else { section = 'pets' }"
                      class="flex w-full items-center justify-between py-3.5 text-sm font-medium text-white">
                Kisállatok
                <x-icon name="chevron-right" class="h-4 w-4 text-white/40 transition-transform duration-200" x-bind:class="{ 'rotate-90': section === 'pets' }" />
              </button>
              <div x-show="section === 'pets'" x-cloak class="pb-3 pl-3 flex flex-col gap-1">
                <a href="{{ route('pets.create') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Új kisállat</a>
                <a href="{{ route('pets.index') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Összes kisállat</a>
                <a href="{{ route('pets.update.index') }}" class="block py-2 text-sm text-white/60 hover:text-white transition-colors">Saját kisállatok</a>
              </div>
            </div>
          @endif

        @else

          <a href="{{ route('shelters.index') }}" class="flex items-center py-3.5 text-sm font-medium text-white border-b border-white/10">Menhelyek</a>
          <a href="{{ route('pets.index') }}" class="flex items-center py-3.5 text-sm font-medium text-white border-b border-white/10">Kisállatok</a>
          @guest
            <a href="{{ route('login') }}" class="flex items-center py-3.5 text-sm font-medium text-white border-b border-white/10">Bejelentkezés</a>
            <a href="{{ route('role') }}" class="flex items-center py-3.5 text-sm font-medium text-white">Regisztráció</a>
          @endguest

        @endif

        @auth
          <div class="py-4">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                      class="w-full rounded-xl bg-white/10 py-3 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15 transition-colors">
                Kijelentkezés
              </button>
            </form>
          </div>
        @endauth

      </div>
    </div>

  </x-slot:dropdown>

</x-navbar.base>
