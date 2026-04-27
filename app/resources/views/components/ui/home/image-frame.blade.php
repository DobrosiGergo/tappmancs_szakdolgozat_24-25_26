@props(['src' => '', 'alt' => ''])
<div class="relative">
  <div class="absolute -top-4 left-4 w-[99%] h-[800px] bg-neutral-500 rounded-[300px]"></div>
  <div class="relative rounded-3xl overflow-hidden">
  <x-ui.lazy-image
            src="{{ $src }}"
            alt="{{ $alt }}"
            class="rounded-[350px] h-[800px] w-[98%] object-cover"
            />
  </div>
</div>
