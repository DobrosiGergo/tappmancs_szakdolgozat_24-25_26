@props(['href' => '#'])
<a
  href="{{ $href }}"
  {{ $attributes->merge(['class' => 'text-sm text-white/80 hover:text-white transition-colors duration-200 block']) }}
>
  {{ $slot }}
</a>
