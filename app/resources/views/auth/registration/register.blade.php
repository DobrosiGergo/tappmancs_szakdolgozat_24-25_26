<x-guest-layout>
  <x-ui.stepper active="account" class="mb-12" />

  <div class="flex flex-col md:flex-row gap-12">
    <div class="w-full md:w-1/2 p-8">
      <h2 class="text-3xl font-semibold mb-8 tracking-tight text-neutral-900">
        Hozz létre saját fiókot
      </h2>

      <form method="POST" action="{{ route('register') }}" class="space-y-8">
        @csrf
        <input type="hidden" name="role" value="{{ request('role') }}">
        <input type="hidden" name="role_shelter" value="{{ request('role_shelter') }}">

        <x-ui.input-floating
          id="email"
          name="email"
          type="email"
          label="Email"
          required="true"
          autocomplete="username"
        />

        <x-ui.input-floating
          id="name"
          name="name"
          type="text"
          label="Teljes név"
          required="true"
          autocomplete="name"
        />

        <x-ui.input-floating
          id="password"
          name="password"
          type="password"
          label="Jelszó"
          required="true"
          autocomplete="new-password"
        />

        <x-ui.input-floating
          id="password_confirmation"
          name="password_confirmation"
          type="password"
          label="Jelszó megerősítés"
          required="true"
          autocomplete="new-password"
        />

        <x-ui.input-floating
          id="phoneNumber"
          name="phoneNumber"
          type="text"
          label="Telefonszám +36-"
          autocomplete="tel"
        />

        <button
          type="submit"
          class="w-full mt-2 bg-neutral-900 text-white py-3 rounded-full font-medium hover:bg-neutral-800 transition"
        >
          Fiók létrehozása
        </button>
      </form>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center">
    <x-ui.lazy-image
    src="{{ asset('images/collar-dog.svg') }}"
    alt="Kutya nyakörv"
    w="640" h="420"
    class="md:w-[520px] lg:w-[600px] border-2 border-neutral-800 rounded-full"
    />
    </div>
  </div>
</x-guest-layout>
