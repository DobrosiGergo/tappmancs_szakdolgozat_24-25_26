<div class="flex flex-col gap-1.5">
  @guest
    <x-navbar.link :href="route('login')">Bejelentkezés</x-navbar.link>
    <x-navbar.link :href="route('role')">Regisztráció</x-navbar.link>
  @endguest
  @auth
    <x-navbar.link :href="route('settings.index')">Beállítások</x-navbar.link>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="text-sm text-white/80 hover:text-white hover:font-semibold transition-colors duration-150 text-left w-full">
        Kijelentkezés
      </button>
    </form>
  @endauth
</div>
