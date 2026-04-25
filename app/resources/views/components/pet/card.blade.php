@props([
  'href' => '#',
  'title' => null,
  'name' => null,
  'description' => '',
  'badge' => null,
  'image' => null,
  'shelterName' => null,
  'meta' => [],
])

@php
  $heading = $title ?: ($name ?: '');

  $imgUrl = null;
  if (!empty($image)) {
      $imgUrl = \Illuminate\Support\Facades\Storage::url($image);
  }

  $metaRows = [];
  foreach ($meta as $key => $value) {
      if ($value !== null && $value !== '') {
          $metaRows[$key] = $value;
      }
  }
@endphp

<a href="{{ $href }}" class="group block h-full">
  <div class="relative h-full overflow-hidden rounded-2xl border border-neutral-200/60 bg-white/80 backdrop-blur-sm shadow-sm transition-all hover:-translate-y-1 hover:shadow-2xl">
    <div class="flex h-full">
      <div class="relative w-36 shrink-0 overflow-hidden bg-gradient-to-br from-neutral-100 to-neutral-200 sm:w-40">
        @if($imgUrl)
          <img
            src="{{ $imgUrl }}"
            alt="{{ $heading }}"
            class="h-full w-full min-h-[180px] object-cover"
          />
          @else
            <div class="flex h-full min-h-[180px] w-full items-center justify-center bg-neutral-100">
              <img
                src="{{ asset('images/pet-placeholder.png') }}"
                alt="Nincs feltöltött kép"
                class="h-20 w-20 object-contain opacity-70"
              >
            </div>
          @endif

        <div class="absolute inset-y-0 right-0 w-px bg-neutral-300/40"></div>

        @if(!empty($badge))
          <div class="absolute left-3 top-3">
            <span class="inline-flex items-center rounded-full border border-neutral-200 bg-white/90 px-2.5 py-1 text-xs font-medium text-neutral-800 shadow-sm backdrop-blur">
              {{ $badge }}
            </span>
          </div>
        @endif
      </div>

      <div class="flex min-w-0 flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <h3 class="truncate text-lg font-semibold text-neutral-900 group-hover:underline">
              {{ $heading }}
            </h3>

            @if(!empty($shelterName))
              <div class="mt-1 text-xs font-medium text-neutral-500">
                {{ $shelterName }}
              </div>
            @endif
          </div>

          <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-neutral-300 text-neutral-600 transition group-hover:bg-neutral-900 group-hover:text-white">
            <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4">
          </span>
        </div>

        @if(!empty($description))
          <p class="mt-2 line-clamp-2 text-sm leading-6 text-neutral-600">
            {{ $description }}
          </p>
        @endif

        @if(!empty($metaRows))
          <dl class="mt-4 grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
            @foreach($metaRows as $key => $value)
              <div class="min-w-0">
                <dt class="text-neutral-500">{{ $key }}</dt>
                <dd class="font-medium text-neutral-800 break-words">{{ $value }}</dd>
              </div>
            @endforeach
          </dl>
        @endif
      </div>
    </div>
  </div>
</a>