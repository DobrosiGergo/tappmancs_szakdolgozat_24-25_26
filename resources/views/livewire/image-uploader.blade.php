<div>
  <label class="block text-sm font-medium text-neutral-700 mb-2">
    Kép feltöltés
  </label>

  <div
    class="flex w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-neutral-300 p-6 text-center text-sm text-neutral-500 transition hover:bg-neutral-50"
    onclick="document.getElementById('imageUpload-{{ $uid }}').click()"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-10 w-10 text-neutral-400" fill="none"
      viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 15a4 4 0 004 4h10a4 4 0 004-4M7 10l5-5m0 0l5 5m-5-5v12" />
    </svg>

    <span class="font-medium">Válassza ki képeit vagy húzza ide</span>
    <span class="mt-1 text-xs text-neutral-400">JPEG, JPG, PNG</span>

    <input
      type="file"
      multiple
      wire:model="images"
      id="imageUpload-{{ $uid }}"
      class="hidden"
    >
  </div>

  @if (!empty($existingImages))
  <div class="mt-6">
    <h3 class="mb-3 text-sm font-medium text-neutral-700">
      Jelenlegi képek
    </h3>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
      @foreach ($existingImages as $index => $image)
        <div class="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100">
          <x-ui.lazy-image
            src="{{ Storage::url($image['path']) }}"
            alt=""
            class="h-32 w-full object-cover"
          />

          <button
            type="button"
            wire:click="removeExistingImage({{ $index }})"
            class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-lg text-white opacity-0 transition group-hover:opacity-100"
          >
            &times;
          </button>
        </div>
      @endforeach
    </div>
  </div>
@endif

@if ($previews)
  <div class="mt-6">
    <h3 class="mb-3 text-sm font-medium text-neutral-700">
      Új képek
    </h3>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
      @foreach ($previews as $index => $image)
        <div class="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100">
          <x-ui.lazy-image
            src="{{ Storage::url($image['path']) }}"
            alt=""
            class="h-32 w-full object-cover"
          />

          <button
            type="button"
            wire:click="removeImage({{ $index }})"
            class="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-lg text-white opacity-0 transition group-hover:opacity-100"
          >
            &times;
          </button>
        </div>
      @endforeach
    </div>
  </div>
@endif

  @error('images')
    <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
  @enderror
</div>