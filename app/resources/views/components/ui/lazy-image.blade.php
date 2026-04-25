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
  {{ $eager ? 'loading=eager fetchpriority=high' : 'loading=lazy' }}
  decoding="async"
  @if($w) width="{{ $w }}" @endif
  @if($h) height="{{ $h }}" @endif
  {{ $attributes->merge(['class' => $class]) }}
/>
