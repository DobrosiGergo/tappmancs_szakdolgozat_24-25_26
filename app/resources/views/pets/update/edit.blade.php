<x-app-layout>
  <section class="max-w-7xl mx-auto px-6 pb-16 pt-8">

    <div class="mb-8">
      <a href="{{ route('pets.update.index') }}"
         class="inline-flex items-center gap-1.5 text-sm text-neutral-500 hover:text-neutral-900 transition mb-3">
        <img src="{{ asset('images/prev.svg') }}" alt="" class="h-4 w-4">
        Vissza
      </a>
      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Kisállat módosítása</h1>
      <p class="mt-1 text-sm text-neutral-500">Módosítsd az adatokat, vagy kezeld a feltöltött képeket.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-8 items-start">

      <div class="bg-white rounded-3xl shadow-sm border border-neutral-200 overflow-hidden">

        @if ($errors->any())
          <div class="p-6 border-b border-neutral-100">
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
              <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('pets.update', $pet) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="hidden" name="shelter_id" value="{{ $shelter->id }}">

          <div class="divide-y divide-neutral-100">

            <div class="p-8 space-y-5">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Alapadatok</p>
              <x-ui.input-floating
                id="name"
                name="name"
                label="Név*"
                required="true"
                :value="old('name', $pet->name)"
              />
            </div>

            <div class="p-8 space-y-5">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Faj és fajta</p>
              @livewire('pet-species-breed-select', [
                'speciesId' => old('species_id', $pet->species_id),
                'breedId'   => old('breed_id', $pet->breed_id),
              ])
            </div>

            <div class="p-8 space-y-5">
              <p class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Részletek</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-ui.input-select
                  id="gender"
                  name="gender"
                  label="Nem*"
                  required="true"
                  :value="old('gender', $pet->gender ?? 'unknown')"
                  :options="\App\Models\Pet::genderOptions()"
                />
                <x-ui.input-select
                  id="status"
                  name="status"
                  label="Státusz*"
                  required="true"
                  :value="old('status', $pet->status)"
                  :options="\App\Models\Pet::statusOptions()"
                />
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <x-ui.input-date
                  id="birth_date"
                  name="birth_date"
                  label="Születési dátum*"
                  required="true"
                  :value="old('birth_date', $pet->birth_date?->format('Y-m-d') ?? now()->format('Y-m-d'))"
                  :max="now()->format('Y-m-d')"
                />
                <x-ui.input-date
                  id="arrival_date"
                  name="arrival_date"
                  label="Érkezés dátuma*"
                  required="true"
                  :value="old('arrival_date', $pet->arrival_date?->format('Y-m-d') ?? now()->format('Y-m-d'))"
                  :max="now()->format('Y-m-d')"
                />
              </div>
            </div>

            <div class="p-8 space-y-5">
              <x-ui.input-floating
                id="description"
                name="description"
                label="Leírás*"
                rows="5"
                required="true"
                :value="old('description', $pet->description)"
              />
            </div>

            <div class="px-8 py-6 bg-neutral-50/60">
              <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
                Kisállat adatainak mentése
              </x-primary-button>
            </div>

          </div>
        </form>
      </div>

      <div class="lg:sticky lg:top-6 bg-white rounded-3xl shadow-sm border border-neutral-200 p-6 lg:p-8">
        <div class="mb-5">
          <h2 class="text-base font-semibold text-neutral-900">Képek</h2>
          <p class="mt-1 text-sm text-neutral-500">Kezeld a meglévő képeket, vagy tölts fel újakat.</p>
        </div>
        @livewire('image-uploader', [
          'context' => 'pet',
          'max'     => 10,
          'maxSize' => 4096,
          'petId'   => $pet->id,
        ])
      </div>

    </div>
  </section>
</x-app-layout>
