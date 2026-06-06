<x-guest-layout>
  <x-ui.stepper active="account" class="mb-12" />

  <div class="flex flex-col md:flex-row gap-12">
    <div class="w-full md:w-1/2 p-8">
      <h2 class="text-3xl font-semibold mb-8 tracking-tight text-neutral-900">
        Hozz létre saját fiókot
      </h2>

      @livewire('register-form', [
        'role'         => request('role', ''),
        'role_shelter' => request('role_shelter', ''),
      ])
    </div>

    <div class="hidden md:flex w-full md:w-1/2 items-center justify-center">
      <x-ui.lazy-image
        src="{{ asset('images/collar-dog.svg') }}"
        alt="Kutya nyakörv"
        w="640" h="420"
        class="w-full max-w-[440px] lg:max-w-[560px] border-2 border-neutral-800 rounded-full"
      />
    </div>
  </div>
</x-guest-layout>
