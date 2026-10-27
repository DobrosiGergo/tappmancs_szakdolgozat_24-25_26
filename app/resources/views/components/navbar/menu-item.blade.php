@props(['label' => '', 'href' => '#', 'dark' => false])
<a
  href="{{ $href }}"
  {{ $attributes->merge(['class' => 'text-sm font-medium transition-colors ease-out']) }}
  :class="{ 'text-white/70 hover:text-white duration-75': @json($dark) || shaded, 'text-neutral-700 hover:text-neutral-900 duration-200': !@json($dark) && !shaded }"
>
  {{ $label }}
</a>
