@props(['href' => '#'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'transition-colors duration-200']) }}>
  {{ $slot }}
</a>
