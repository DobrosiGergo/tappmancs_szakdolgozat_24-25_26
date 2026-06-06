@props([
  'href' => '#',
  'title' => '',
  'description' => '',
  'iconClass' => 'bg-neutral-100 text-neutral-700',
])

<a href="{{ $href }}"
   {{ $attributes->merge([
      'class' => 'group block rounded-2xl border border-neutral-200 bg-white p-6 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition'
   ]) }}>
  <div class="flex gap-4">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $iconClass }}">
      @isset($icon){{ $icon }}@endisset
    </div>
    <div class="flex-1 min-w-0">
      <h3 class="text-base font-semibold text-neutral-900 group-hover:underline">{{ $title }}</h3>
      @if($description)
        <p class="mt-0.5 text-sm text-neutral-500">{{ $description }}</p>
      @endif
    </div>
    <div class="self-center shrink-0">
      <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 group-hover:bg-neutral-900 group-hover:border-neutral-900 group-hover:text-white transition">
        <x-icon name="arrow-right" class="h-4 w-4" />
      </span>
    </div>
  </div>
</a>
