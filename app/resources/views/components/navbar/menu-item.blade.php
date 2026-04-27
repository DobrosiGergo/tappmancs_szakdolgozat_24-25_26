@props(['label' => '', 'href' => '#'])
<a
  href="{{ $href }}"
  {{ $attributes->merge(['class' => 'text-sm font-medium transition-colors duration-200']) }}
  :class="open !== null ? 'text-white/70 hover:text-white hover:font-semibold' : 'text-neutral-700 hover:text-neutral-900 hover:font-semibold'"
>
  {{ $label }}
</a>
