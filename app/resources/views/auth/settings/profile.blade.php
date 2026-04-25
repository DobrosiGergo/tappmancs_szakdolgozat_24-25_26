<x-app-layout>
  <x-ui.container class="py-10 max-w-3xl">

    <div class="bg-white shadow-sm rounded-2xl p-8 border border-neutral-200">
      <div class="flex items-center gap-4 mb-6">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
          <img src="{{ asset('images/profile.svg') }}" alt="" class="h-8 w-8">
        </div>
        <div>
          <h2 class="text-2xl font-semibold text-neutral-900">Profiladatok módosítása</h2>
          <p class="text-sm text-neutral-600 mt-1">
            Frissítsd a neved és az e-mail címed. Ha az e-mailt módosítod, szükség lesz újra-hitelesítésre.
          </p>
        </div>
      </div>

      <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
      </form>

      <form method="post" action="{{ route('settings.profile.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
          <x-input-label for="name" value="Név" />
          <x-text-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            :value="old('name', $user->name)"
            required
            autofocus
            autocomplete="name"
          />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
          <x-input-label for="email" value="E-mail cím" />
          <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full"
            :value="old('email', $user->email)"
            required
            autocomplete="username"
          />
          <x-input-error class="mt-2" :messages="$errors->get('email')" />

          @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 rounded-xl bg-amber-50 border border-amber-200 p-4">
              <p class="text-sm text-amber-800">
                Az e-mail címed még nincs hitelesítve.
                <button form="send-verification"
                        class="ml-1 inline-flex items-center gap-1 underline text-amber-900 hover:text-amber-700 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-400">
                  Hitelesítő levél újraküldése
                </button>
              </p>

              @if (session('status') === 'verification-link-sent')
                <div
                  x-data="{ show: true }"
                  x-show="show"
                  x-transition
                  x-init="setTimeout(() => show = false, 2000)"
                  class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1"
                >
                <img src="{{ asset('images/check.svg') }}" alt="" class="h-4 w-4">
                <span>Hitelesítő link elküldve</span>
                </div>
              @endif
            </div>
          @endif
        </div>

        <div class="flex items-center gap-4">
          <x-primary-button class="px-6 py-2 text-lg">Mentés</x-primary-button>

          @if (session('status') === 'profile-updated')
            <div
              x-data="{ show: true }"
              x-show="show"
              x-transition
              x-init="setTimeout(() => show = false, 2000)"
              class="inline-flex items-center gap-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1"
            >
            <img src="{{ asset('images/check.svg') }}" alt="" class="h-4 w-4">
              <span>Mentve</span>
            </div>
          @endif
        </div>
      </form>
    </div>

  </x-ui.container>
</x-app-layout>
