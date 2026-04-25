@props([
  'title' => '',
  'description' => '',
  'image' => null,
  'primaryHref' => '#',
  'primaryLabel' => '',
  'secondaryHref' => null,
  'secondaryLabel' => null,
])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
  <div>
    <h1 class="text-3xl sm:text-4xl font-bold text-neutral-900">{{ $title }}</h1>
    @if($description)
      <p class="mt-4 text-neutral-600 text-lg leading-7">{{ $description }}</p>
    @endif
    <div class="mt-6 flex flex-col gap-3">
      @if($primaryLabel)
        <a href="{{ $primaryHref }}" class="inline-flex items-center justify-center rounded-xl bg-neutral-900 text-white px-5 py-3 font-medium hover:bg-neutral-800 transition">
          {{ $primaryLabel }}
        </a>
      @endif
      @if($secondaryHref && $secondaryLabel)
        <a href="{{ $secondaryHref }}" class="inline-flex items-center justify-center rounded-xl border border-neutral-300 text-neutral-800 px-5 py-3 font-medium hover:bg-neutral-100 transition">
          {{ $secondaryLabel }}
        </a>
      @endif
    </div>
  </div>

  @if($image)
    <div class="relative">
      <div class="absolute -inset-4 rounded-3xl bg-gradient-to-tr from-neutral-200/30 to-neutral-100/30 blur-2xl"></div>
      <x-ui.lazy-image
            src="{{ $image }}"
            alt=""
            class="relative w-full max-w-xl mx-auto rounded-3xl shadow-xl ring-1 ring-neutral-200/60 object-cover"
            />
    </div>
  @endif
</div>
