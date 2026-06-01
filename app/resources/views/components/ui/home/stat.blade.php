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
  class="group relative inline-block md:mt-8 md:w-[450px] md:h-[130px] {{ $class }}">
  <div aria-hidden="true" class="absolute top-4 left-4 w-full h-full bg-muted rounded-3xl"></div>
  <div class="relative bg-dark-mid group-hover:bg-dark-soft text-white p-10 rounded-3xl text-center w-full md:w-[450px] shadow-md transition-all duration-300 ease-out group-hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]">
    <p class="text-2xl text-white">{{ $displayValue }}</p>
    <p class="text-md text-white">{{ $label }}</p>
  </div>
</{{ $tag }}>
