@props([
  'href' => '#',
  'name' => '',
  'description' => '',
  'badge' => null,
  'image' => null,
  'shelterName' => null,
  'meta' => [],
])

@php
  $imgUrl = $image ? \Illuminate\Support\Facades\Storage::url($image) : '';

  $metaRows = [];
  foreach ($meta as $key => $value) {
      if ($value !== null && $value !== '') {
          $metaRows[$key] = $value;
      }
  }
@endphp

<a href="{{ $href }}" class="group block h-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-900 focus-visible:ring-offset-2 rounded-2xl">
  <article class="relative flex h-full flex-col overflow-hidden rounded-2xl border border-neutral-200/70 bg-white shadow-sm transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neutral-200/60">

    <div class="relative h-52 shrink-0 overflow-hidden bg-neutral-100">
      @if($imgUrl)
        <img
          src="{{ $imgUrl }}"
          alt="{{ $name }}"
          class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
        />
      @else
        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
          <img
            src="{{ asset('images/pet-placeholder.png') }}"
            alt="Nincs feltöltött kép"
            class="h-16 w-16 object-contain opacity-40"
          >
        </div>
      @endif

      <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/25 to-transparent pointer-events-none"></div>

      @if(!empty($badge))
        <div class="absolute left-3 top-3">
          <span class="inline-flex items-center rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-neutral-800 shadow-sm backdrop-blur-sm ring-1 ring-white/60">
            {{ $badge }}
          </span>
        </div>
      @endif
    </div>

    <div class="h-px w-0 bg-neutral-900 transition-all duration-500 ease-out group-hover:w-full"></div>

    <div class="flex flex-1 flex-col p-5">

      <h3 class="text-xl font-semibold leading-snug text-neutral-900 transition-colors duration-200 group-hover:text-neutral-600 line-clamp-1">
        {{ $name }}
      </h3>

      @if(!empty($shelterName))
        <p class="mt-1 text-xs font-medium uppercase tracking-widest text-neutral-400">
          {{ $shelterName }}
        </p>
      @endif

      @if(!empty($description))
        <p class="mt-2.5 line-clamp-2 text-sm leading-relaxed text-neutral-500">
          {{ $description }}
        </p>
      @endif

      @if(!empty($metaRows))
        <dl class="mt-auto grid grid-cols-3 gap-x-3 gap-y-1 border-t border-neutral-100 pt-4 text-xs">
          @foreach($metaRows as $key => $value)
            <div class="min-w-0">
              <dt class="text-neutral-400">{{ $key }}</dt>
              <dd class="mt-0.5 truncate font-medium text-neutral-700">{{ $value }}</dd>
            </div>
          @endforeach
        </dl>
      @endif

    </div>

    <div class="absolute bottom-4 right-4">
      <span class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 opacity-0 transition-all duration-300 group-hover:opacity-100 group-hover:bg-neutral-900 group-hover:border-neutral-900" aria-hidden="true">
        <x-icon name="arrow-right" class="h-3.5 w-3.5 transition-colors group-hover:text-white" />
      </span>
    </div>

  </article>
</a>
