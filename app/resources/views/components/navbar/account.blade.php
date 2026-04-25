<div class="flex flex-col gap-2">
  @guest
    <x-navbar.link :href="route('login')" class="text-white hover:text-black hover:font-bold">Bejelentkezés</x-navbar.link>
    <x-navbar.link :href="route('role')" class="text-white hover:text-black hover:font-bold">Regisztráció</x-navbar.link>
  @endguest
  @auth
    <x-navbar.link :href="route('settings.index')" class="text-white hover:text-black hover:font-bold">Beállítások</x-navbar.link>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="text-left w-full text-white hover:text-black hover:font-bold transition">Kijelentkezés</button>
    </form>
  @endauth
</div>
