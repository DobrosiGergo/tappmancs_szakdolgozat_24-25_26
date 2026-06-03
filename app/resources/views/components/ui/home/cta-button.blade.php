@props(['href' => '#', 'label' => ''])

<div class="relative w-full md:mt-8 md:w-[673px] md:h-[110px]">
  <div aria-hidden="true" class="absolute top-2 left-2 md:top-3 md:left-3 w-full h-full bg-muted rounded-full"></div>

  <a href="{{ $href }}"
     class="relative flex items-center justify-center w-full h-full bg-dark-mid hover:bg-dark-soft text-white text-lg md:text-3xl rounded-full shadow-md px-8 py-4 md:px-0 md:py-0
            transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]"
     data-test-id="learn-more-button">
    {{ $label }}
  </a>
</div>
