@props([
  'src' => '',
  'alt' => '',
  'w' => null,
  'h' => null,
  'eager' => false,
  'class' => '',
])

<img
  src="{{ $src }}"
  alt="{{ $alt }}"
  @if($eager) loading="eager" fetchpriority="high" @else loading="lazy" @endif
  decoding="async"
  @if($w) width="{{ $w }}" @endif
  @if($h) height="{{ $h }}" @endif
  {{ $attributes->merge(['class' => $class]) }}
/>
