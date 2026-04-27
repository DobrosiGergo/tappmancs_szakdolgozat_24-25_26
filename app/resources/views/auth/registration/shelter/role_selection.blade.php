<x-guest-layout>
  <div class="max-w-5xl mx-auto px-6">
    <x-ui.stepper active="shelterRole" class="mb-12" />

    <section>
      <h1 class="text-4xl md:text-5xl font-semibold text-neutral-900 mb-8">Szerepkörök információ</h1>

      <div class="space-y-6 text-neutral-800 text-lg leading-8 mb-12">
        <p><strong>Menhely dolgozó:</strong> a képviselt menhely adatait kezeli és karbantartja, ezzel biztosítva a megfelelő működést és nyilvántartást.</p>
        <p><strong>Menhely tulajdonos:</strong> a saját menhely karbantartásáért és kezeléséért felel, valamint a dolgozók felvételéért és adminisztrációjáért.</p>
      </div>

      <div class="flex flex-col sm:flex-row gap-8 justify-center items-center relative">
        <form method="POST" action="{{ route('registration.shelter.role_selection_store') }}">
          @csrf
          <button
            type="submit"
            name="role_shelter"
            value="shelterWorker"
            class="group w-72 rounded-3xl border border-neutral-200 bg-white p-8
                   flex flex-col items-center justify-center gap-4 text-center shadow-sm
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-neutral-300
                   focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
          >
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-700">
            <x-ui.lazy-image
            src="{{ asset('images/profile.svg') }}"
            alt=""
            class="h-7 w-7"
            />
            </span>
            <span class="block text-xl font-semibold text-neutral-900">Menhely dolgozó</span>
            <span class="text-sm text-neutral-600">Kattints a folytatáshoz</span>
          </button>
        </form>

        <form method="POST" action="{{ route('registration.shelter.role_selection_store') }}">
          @csrf
          <button
            type="submit"
            name="role_shelter"
            value="shelterOwner"
            class="group w-72 rounded-3xl border border-neutral-200 bg-white p-8
                   flex flex-col items-center justify-center gap-4 text-center shadow-sm
                   transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:border-neutral-300
                   focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2"
          >
            <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-neutral-100 text-neutral-700">
            <x-ui.lazy-image
            src="{{ asset('images/pet-shelter-svgrepo-com.svg') }}"
            alt=""
            class="h-7 w-7"
            />
            </span>
            <span class="block text-xl font-semibold text-neutral-900">Menhely tulajdonos</span>
            <span class="text-sm text-neutral-600">Kattints a folytatáshoz</span>
          </button>
        </form>
        <x-ui.lazy-image
            src="{{ asset('images/standing-dog.svg') }}"
            alt=""
            class="hidden md:block pointer-events-none select-none absolute -right-32 bottom-0
                 h-[380px] opacity-90"
            />
      </div>
    </section>
  </div>
</x-guest-layout>
