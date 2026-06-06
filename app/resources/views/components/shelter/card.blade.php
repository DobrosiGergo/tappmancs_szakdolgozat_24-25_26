@props([
  'href' => '#',
  'name' => '',
  'description' => '',
  'badge' => null,
  'image' => null,
  'location' => null,
])

@php
  use Illuminate\Support\Facades\Storage;

  if ($image) {
      $imgUrl = Storage::disk('public')->url($image);
  } else {
      $imgUrl = '';
  }
@endphp

<a href="{{ $href }}" class="group block h-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-900 focus-visible:ring-offset-2 rounded-2xl">
  <article class="relative flex h-full flex-col overflow-hidden rounded-2xl border border-neutral-200/70 bg-white shadow-sm transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neutral-200/60">

    <div class="relative flex h-44 shrink-0 items-center justify-center overflow-hidden bg-neutral-700">
      @if($imgUrl)
        <img
          src="{{ $imgUrl }}"
          alt="{{ $name }}"
          class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 opacity-70"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 to-transparent pointer-events-none"></div>
      @else
        <x-shelter.icon class="h-10 w-10 brightness-0 invert opacity-70 transition-transform duration-500 ease-out group-hover:scale-110" />
      @endif
    </div>

    <div class="h-px w-0 bg-neutral-900 transition-all duration-500 ease-out group-hover:w-full"></div>

    <div class="flex flex-1 flex-col p-5">
      <h3 class="text-xl font-semibold leading-snug text-neutral-900 transition-colors duration-200 group-hover:text-neutral-600">
        {{ $name }}
      </h3>

      @if($description)
        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-500">
          {{ $description }}
        </p>
      @endif

      @if($location)
        <p class="mt-2 flex items-center gap-1 text-xs text-neutral-400">
          <x-icon name="map-pin" class="h-3 w-3 shrink-0" />
          {{ $location }}
        </p>
      @endif

      <div class="mt-auto flex items-center justify-between pt-4">
        @if($badge)
          <span class="inline-flex items-center rounded-full bg-neutral-100 px-3 py-1 text-xs font-medium text-neutral-700 ring-1 ring-neutral-200">
            {{ $badge }}
          </span>
        @else
          <span></span>
        @endif

        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-neutral-200 text-neutral-600 transition-all duration-300 group-hover:bg-neutral-900 group-hover:border-neutral-900 group-hover:translate-x-0.5" aria-hidden="true">
          <x-icon name="arrow-right" class="h-4 w-4 transition-colors duration-300 group-hover:text-white" />
        </span>
      </div>
    </div>

  </article>
</a>
