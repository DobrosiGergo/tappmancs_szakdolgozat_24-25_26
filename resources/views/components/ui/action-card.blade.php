@props([
  'href' => '#',
  'title' => '',
  'description' => '',
])

<a href="{{ $href }}"
   {{ $attributes->merge([
      'class' => 'group block rounded-2xl border border-neutral-200 bg-white p-6 md:p-8 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition'
   ]) }}>
  <div class="flex gap-4">
    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-neutral-100 text-neutral-700">
      {{ $icon ?? '' }}
    </div>
    <div class="flex-1">
      <h3 class="text-xl md:text-2xl font-semibold text-neutral-900 group-hover:underline">{{ $title }}</h3>
      @if($description)
        <p class="mt-1 text-neutral-600">{{ $description }}</p>
      @endif
    </div>
    <div class="self-center">
      <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-neutral-300 text-neutral-700 group-hover:bg-neutral-900 group-hover:text-white transition">
        <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4">
      </span>
    </div>
  </div>
</a>
