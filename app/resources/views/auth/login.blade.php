<x-guest-layout>
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <section class="hidden lg:flex items-center justify-center relative">
      <div class="absolute inset-0 bg-[#333333]"></div>
      <div class="absolute inset-0" style="clip-path: polygon(58% 0,100% 0,100% 100%,30% 100%); background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));"></div>
      <div class="relative z-10 max-w-md text-white px-12">
        <h1 class="text-3xl font-semibold tracking-tight">Üdv újra a Központban</h1>
        <p class="mt-2 text-neutral-200/90">Jelentkezz be, kezeld a menhelyed adatait, tölts fel képeket és segíts gazdit találni.</p>
        <div class="mt-10">
          <x-ui.lazy-image
            src="{{ asset('images/pet-svgrepo-com.svg') }}"
            alt="Kutya nyakörv"
            w="640" h="420"
          />
        </div>
      </div>
    </section>

    <section class="flex items-center justify-center p-6 sm:p-10">
      <div class="w-full max-w-[440px]">
        <div class="mb-6">
          <h2 class="text-2xl font-semibold text-neutral-900">Bejelentkezés</h2>
          <p class="mt-1 text-neutral-600 text-sm">Folytasd a munkát vagy böngéssz a kisállatok között.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-7">
          @csrf

          <x-ui.input-floating
            id="email"
            name="email"
            type="email"
            label="E-mail"
            required="true"
            value="{{ old('email') }}"
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />

          <div>
            <x-ui.input-floating
              id="password"
              name="password"
              type="password"
              label="Jelszó"
              required="true"
            />
            @if (Route::has('password.request'))
              <div class="mt-2 text-right">
                <a class="text-sm text-neutral-600 hover:text-neutral-900" href="{{ route('password.request') }}">Elfelejtette a jelszavát?</a>
              </div>
            @endif
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
              <input id="remember_me" type="checkbox" class="rounded border-neutral-300 text-neutral-900 focus:ring-neutral-600" name="remember">
              <span class="ms-2 text-sm text-neutral-700">Emlékezz rám</span>
            </label>
            <a class="text-sm text-neutral-600 hover:text-neutral-900" href="{{ route('role') }}">Regisztráció</a>
          </div>

          <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
            Bejelentkezés
          </x-primary-button>
        </form>
      </div>
    </section>
  </div>
</x-guest-layout>
