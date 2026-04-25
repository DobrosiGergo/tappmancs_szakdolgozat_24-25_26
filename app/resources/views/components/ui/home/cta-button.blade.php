@props(['href' => '#', 'label' => ''])

<div class="relative inline-block md:mt-8 md:w-[673px] md:h-[110px]">
  <div class="absolute top-3 left-3 w-full h-full bg-neutral-500 rounded-full"></div>

  <a href="{{ $href }}"
     class="relative flex items-center justify-center w-full h-full bg-[#333333] text-white md:text-3xl rounded-full shadow-md 
            transition-all before:absolute before:right-0 before:top-0 before:h-full before:w-6 before:translate-x-24 before:rotate-8 
            before:bg-white before:opacity-10 before:duration-700 hover:shadow-white hover:before:-translate-x-[673px]"
     data-test-id="learn-more-button">
    {{ $label }}
  </a>
</div>
