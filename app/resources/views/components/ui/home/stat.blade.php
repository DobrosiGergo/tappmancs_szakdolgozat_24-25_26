@props(['value' => '', 'label' => '', 'class' => ''])
<div class="relative inline-block md:mt-8 md:w-[450px] md:h-[130px] {{ $class }}">
  <div class="absolute top-4 left-4 w-full h-full bg-neutral-500 rounded-3xl"></div>
  <div class="relative bg-[#333333] text-white p-10 rounded-3xl text-center w-full md:w-[450px]">
    <p class="text-2xl text-white">{{ $value }}</p>
    <p class="text-md text-white">{{ $label }}</p>
  </div>
</div>
