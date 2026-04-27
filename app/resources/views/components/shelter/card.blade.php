@props([
  'href' => '#',
  'title' => null,
  'name' => null,
  'description' => '',
  'badge' => null,
])

@php $heading = $title ?? $name ?? ''; @endphp

<a href="{{ $href }}" class="group block">
  <div class="relative overflow-hidden rounded-2xl border border-neutral-200/60 bg-white/80 backdrop-blur-sm shadow-sm transition-all hover:-translate-y-1 hover:shadow-2xl">
    <div class="flex">
      <div class="relative w-24 shrink-0 flex items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
        <x-shelter.icon class="h-10 w-10 text-neutral-700 group-hover:scale-110 transition-transform" />
        <div class="absolute inset-y-0 right-0 w-px bg-neutral-300/40"></div>
      </div>

      <div class="flex-1 p-5">
        <div class="flex items-start justify-between gap-3">
          <h3 class="text-lg font-semibold text-neutral-900 group-hover:text-black group-hover:underline">
            {{ $heading }}
          </h3>
          <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-neutral-300 text-neutral-600 transition group-hover:bg-neutral-900 group-hover:text-white">
            <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4">
          </span>
        </div>

        @if($description)
          <p class="mt-2 text-sm leading-6 text-neutral-600">
            {{ $description }}
          </p>
        @endif

        <div class="mt-3">
          @if($badge)
            <span class="inline-flex items-center rounded-full bg-neutral-900 px-3 py-1 text-xs font-medium text-white shadow-sm group-hover:bg-neutral-700 transition">
              {{ $badge }}
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>
</a>
