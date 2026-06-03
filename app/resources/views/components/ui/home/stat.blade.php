@props(['value' => '', 'label' => '', 'class' => '', 'target' => null, 'suffix' => '', 'href' => null])

@php
  if ($target !== null) {
      $displayValue = number_format($target, 0, ',', ' ') . $suffix;
  } else {
      $displayValue = $value;
  }

  if ($href) {
      $tag = 'a';
  } else {
      $tag = 'div';
  }
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
  class="group relative {{ $class }}">
  <div aria-hidden="true" class="absolute top-2 left-2 md:top-4 md:left-4 w-full h-full bg-muted rounded-2xl md:rounded-3xl"></div>
  <div class="relative bg-dark-mid group-hover:bg-dark-soft text-white
              px-6 py-5 md:px-10 md:py-8
              rounded-2xl md:rounded-3xl text-center
              shadow-md transition-all duration-300 ease-out
              group-hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]
              min-w-[120px] md:min-w-[180px]">
    <p class="text-xl md:text-3xl font-semibold text-white">{{ $displayValue }}</p>
    <p class="text-sm md:text-base text-white/80 mt-0.5">{{ $label }}</p>
  </div>
</{{ $tag }}>
