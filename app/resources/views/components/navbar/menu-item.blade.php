@props(['label' => '', 'href' => '#', 'dark' => false])
<a
  href="{{ $href }}"
  {{ $attributes->merge(['class' => 'text-sm font-medium transition-colors duration-200']) }}
  :class="{ 'text-white/70 hover:text-white': @json($dark) || open !== null, 'text-neutral-700 hover:text-neutral-900': !@json($dark) && open === null }"
>
  {{ $label }}
</a>
