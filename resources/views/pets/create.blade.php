<x-app-layout>
  <section class="max-w-7xl mx-auto px-6 pb-16 pt-8">
    <div class="grid grid-cols-1 lg:grid-cols-[3fr_2fr] gap-8 items-start">

      <div class="bg-white rounded-3xl shadow-md border border-neutral-200 p-8 lg:p-10">
        <div class="mb-6">
          <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">Új kisállat felvétele</h1>
          <p class="mt-2 text-neutral-600">Add meg az alapadatokat. A képeket a jobb oldalon tudod feltölteni.</p>
        </div>

        @if ($errors->any())
          <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('pets.store') }}" class="space-y-10" enctype="multipart/form-data">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.input-floating id="name" name="name" label="Név*" required="true" :value="old('name')" />

            <input type="hidden" name="status" value="free">

            @livewire('pet-species-breed-select')

            <x-ui.input-floating id="age" name="age" type="number" step="0.1" min="0" max="20"
              label="Életkor (év, 0–20)*" required="true" :value="old('age')"
              oninput="if(this.value > 20) this.value = 20; if(this.value < 0) this.value = 0;" />

            <x-ui.input-floating id="arrival_date" name="arrival_date" type="datetime-local"
              label="Érkezés dátuma*" required="true"
              :value="old('arrival_date', now()->format('Y-m-d\TH:i'))" />

            <input type="hidden" name="shelter_id" value="{{ $shelter->id }}">
          </div>

          <x-ui.input-floating id="description" name="description" label="Leírás*" rows="6" required="true" :value="old('description')" />

          <div class="pt-2">
            <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">Kisállat létrehozása</x-primary-button>
          </div>
        </form>
      </div>

      <div class="bg-white rounded-3xl shadow-sm border border-neutral-200 p-6 lg:p-8 self-start">
        <div class="mb-4">
          <h2 class="text-xl font-semibold text-neutral-900">Kép feltöltés</h2>
          <p class="mt-1 text-neutral-600 text-sm">Válaszd ki vagy húzd ide a képeket. Több kép is feltölthető.</p>
        </div>
        @livewire('image-uploader', ['context' => 'pet', 'max' => 10, 'maxSize' => 4096])
      </div>

    </div>
  </section>
</x-app-layout>
