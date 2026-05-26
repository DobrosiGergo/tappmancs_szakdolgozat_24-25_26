<x-app-layout>
  <x-ui.container class="py-10 max-w-3xl">

    <div class="bg-white shadow-sm rounded-2xl p-8 border border-neutral-200">
      <div class="flex items-center gap-4 mb-6">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
          <x-icon name="lock" class="h-8 w-8" />
        </div>
        <div>
          <h2 class="text-2xl font-semibold text-neutral-900">Jelszó módosítása</h2>
          <p class="text-sm text-neutral-600 mt-1">
            Biztonságod érdekében használj hosszú és egyedi jelszót.
          </p>
        </div>
      </div>

      <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
          <x-input-label for="update_password_current_password" value="Jelenlegi jelszó" />
          <x-text-input
            id="update_password_current_password"
            name="current_password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="current-password"
          />
          <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="update_password_password" value="Új jelszó" />
          <x-text-input
            id="update_password_password"
            name="password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
          <x-input-label for="update_password_password_confirmation" value="Új jelszó megerősítése" />
          <x-text-input
            id="update_password_password_confirmation"
            name="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
          <x-primary-button class="px-6 py-2 text-lg">Mentés</x-primary-button>

        </div>
      </form>
    </div>

  </x-ui.container>
</x-app-layout>
