<x-app-layout>
  <x-ui.container class="py-10 max-w-3xl">

    <div class="bg-white shadow-sm rounded-2xl p-8 border border-red-200">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-red-100 text-red-600">
          <img src="{{ asset('images/delete.svg') }}" alt="" class="h-8 w-8">
        </div>
        <div>
          <h2 class="text-2xl font-semibold text-red-700">Fiók törlése</h2>
          <p class="text-sm text-neutral-600 mt-1">
            A fiók és az összes kapcsolódó adat véglegesen törlődni fog. Mielőtt folytatnád,
            mentsd el az adataidat, ha szükséged van rájuk.
          </p>
        </div>
      </div>

      <div class="mt-8">
        <x-danger-button
          x-data=""
          x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
          class="px-6 py-3 text-lg"
        >
          Végleges törlés indítása
        </x-danger-button>
      </div>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
      <form method="post" action="{{ route('settings.delete.confirm') }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-neutral-900">
          Biztosan törölni szeretnéd a fiókodat?
        </h2>

        <p class="mt-2 text-sm text-neutral-600">
          A törlés végleges, és minden kapcsolódó adat is eltávolításra kerül. 
          A folytatáshoz írd be a jelszavadat.
        </p>

        <div class="mt-6">
          <x-input-label for="password" value="Jelszó" class="sr-only" />
          <x-text-input
            id="password"
            name="password"
            type="password"
            class="mt-1 block w-full"
            placeholder="Jelszó"
          />
          <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <x-secondary-button x-on:click="$dispatch('close')">
            Mégse
          </x-secondary-button>

          <x-danger-button>
            Fiók törlése
          </x-danger-button>
        </div>
      </form>
    </x-modal>

  </x-ui.container>
</x-app-layout>
