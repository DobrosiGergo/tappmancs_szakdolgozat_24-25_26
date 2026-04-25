@php
  $isEdit = isset($mode) && $mode === 'edit' && isset($shelter);
  $action = $isEdit ? route('shelter.update', $shelter) : route('shelter.store');
  $btn    = $isEdit ? 'Változások mentése' : 'Menhely létrehozása';
  $title  = $isEdit ? 'Menhely szerkesztése' : 'Hozza létre menhelyét';
  $subtitle = $isEdit
      ? 'Módosítsa az adatokat. Új képeket a jobb oldalon adhat hozzá, a régieket alább tudja törölni.'
      : 'Adj meg egy nevet és rövid leírást. A képeket a jobb oldalon tudod feltölteni.';
@endphp

<x-guest-layout>
  @if (!$isEdit)
    <div class="max-w-7xl mx-auto px-6">
      <x-ui.stepper active="images" role="shelter" roleShelter="shelterOwner" class="mb-8" />
    </div>
  @endif

  <section class="max-w-7xl mx-auto px-6 pb-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

      <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 md:p-8">
        <div class="mb-6">
          <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900">{{ $title }}</h1>
          <p class="mt-2 text-neutral-600">{{ $subtitle }}</p>
        </div>

        <form method="POST" action="{{ $action }}" class="space-y-8">
          @csrf
          @if($isEdit)
            @method('PUT')
          @endif

          <x-ui.input-floating
            id="name"
            name="name"
            label="Menhely név*"
            required="true"
            :value="old('name', $shelter->name ?? '')"
          />

          <x-ui.input-floating
            id="description"
            name="description"
            label="Leírás*"
            rows="5"
            required="true"
            :value="old('description', $shelter->description ?? '')"
          />

          @if($isEdit && !empty($shelter->images_safe))
            <div class="space-y-3">
              <div class="text-sm font-medium text-neutral-900">Meglévő képek</div>
              <div class="grid sm:grid-cols-2 gap-4">
                @foreach($shelter->images_safe as $img)
                  <label class="relative block rounded-xl border border-neutral-200 overflow-hidden bg-white">
                    <img src="{{ asset('storage/'.$img) }}" alt="" class="h-40 w-full object-cover">
                    <div class="flex items-center gap-2 p-2 text-sm">
                      <input type="checkbox" name="remove_images[]" value="{{ $img }}" id="rm_{{ md5($img) }}" class="rounded border-neutral-300 text-neutral-700 focus:ring-neutral-500">
                      <span class="text-neutral-600">Törlés</span>
                    </div>
                  </label>
                @endforeach
              </div>
            </div>
          @endif

          <button
            type="submit"
            class="w-full inline-flex items-center justify-center rounded-full bg-neutral-900 text-white px-5 py-3 font-medium hover:bg-neutral-800 transition"
          >
            {{ $btn }}
          </button>
        </form>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 md:p-8">
        <div class="mb-4">
          <h2 class="text-xl font-semibold text-neutral-900">Kép feltöltés</h2>
          <p class="mt-1 text-neutral-600 text-sm">Válaszd ki vagy húzd ide a képeket (JPEG, JPG, PNG). Több kép is feltölthető.</p>
        </div>

        @livewire('image-uploader', ['context' => 'shelter', 'max' => 10, 'maxSize' => 2048])
        </div>

    </div>
  </section>
</x-guest-layout>
