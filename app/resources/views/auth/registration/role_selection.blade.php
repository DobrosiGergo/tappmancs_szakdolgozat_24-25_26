<x-guest-layout>
  <x-registration.section class="relative">
    <x-ui.stepper active="role" class="mb-10" />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
      <header class="lg:col-span-6">
        <h1 class="text-5xl md:text-6xl font-semibold tracking-tight text-neutral-900">
          Tappmancs
        </h1>
        <p class="mt-5 text-lg md:text-xl text-neutral-700 leading-8">
          <span class="font-semibold">Örökbefogadóként</span> találd meg a hozzád illő négylábú társat.<br class="hidden md:block" />
          <span class="font-semibold">Menhelyként</span> hozz létre profilt, kezeld az adatokat, és kapcsolódj a leendő gazdikhoz.
        </p>

        <div class="mt-10">
          <x-registration.role-grid />
        </div>
      </header>

      <div class="lg:col-span-6 hidden lg:flex justify-center">
      <x-ui.lazy-image
        src="{{ asset('images/cat-dog.svg') }}"
        alt=""
        class="max-h-[520px] -scale-x-100 object-contain opacity-90"
        />
      </div>
    </div>
  </x-registration.section>
</x-guest-layout>
