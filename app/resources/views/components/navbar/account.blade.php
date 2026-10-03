<div class="flex flex-col gap-1.5">
  @guest
    <x-navbar.link :href="route('login')" data-test-id="nav-login">Bejelentkezés</x-navbar.link>
    <x-navbar.link :href="route('role')">Regisztráció</x-navbar.link>
  @endguest
  @auth
    <x-navbar.link :href="route('settings.index')">Beállítások</x-navbar.link>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" data-test-id="nav-logout" class="text-sm text-white/80 hover:text-white transition-colors duration-150 text-left w-full">
        Kijelentkezés
      </button>
    </form>
  @endauth
</div>
