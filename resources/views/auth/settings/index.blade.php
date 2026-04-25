<x-app-layout>
  <x-slot name="header">
    <h2 class="text-2xl font-semibold leading-tight text-neutral-900">
      Beállítások
    </h2>
  </x-slot>

  <x-ui.container class="py-8">
    <div class="grid grid-cols-1 gap-6 max-w-4xl">

      <x-ui.action-card
        class="w-full p-6 md:p-8"
        :href="route('settings.profile')"
        title="Profilinformációk módosítása"
        description="Név, e-mail, avatar frissítése."
      >
        <x-slot:icon>
          <img src="{{ asset('images/profile.svg') }}" alt="" class="h-7 w-7">
        </x-slot:icon>
      </x-ui.action-card>

      <x-ui.action-card
        class="w-full p-6 md:p-8"
        :href="route('settings.password')"
        title="Jelszó módosítása"
        description="Erős, egyedi jelszót állíts be a fiókodhoz."
      >
        <x-slot:icon>
          <img src="{{ asset('images/lock.svg') }}" alt="" class="h-7 w-7">
        </x-slot:icon>
      </x-ui.action-card>

      <x-ui.action-card
        class="w-full p-6 md:p-8 border-red-200"
        :href="route('settings.delete')"
        title="Fiók törlése"
        description="A fiók és az összes kapcsolódó adat végleges eltávolítása."
      >
        <x-slot:icon>
          <img src="{{ asset('images/delete.svg') }}" alt="" class="h-7 w-7">
        </x-slot:icon>
      </x-ui.action-card>
    </div>
  </x-ui.container>
</x-app-layout>
