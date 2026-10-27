@props(['href' => '#', 'label' => ''])

<div class="relative w-full max-w-sm md:inline-block md:w-auto md:max-w-none mt-2 md:mt-4">
  <div aria-hidden="true" class="absolute top-2 left-2 md:top-3 md:left-3 w-full h-full bg-neutral-500 rounded-full"></div>

  <a href="{{ $href }}"
     class="relative flex items-center justify-center bg-neutral-800 hover:bg-neutral-700 text-white
            text-base md:text-lg font-semibold rounded-full shadow-md
            px-8 py-4 md:px-12 md:py-5
            transition-all duration-300 ease-out hover:shadow-[0_0_30px_rgba(255,255,255,0.2)]"
     data-test-id="learn-more-button">
    {{ $label }}
  </a>
</div>
