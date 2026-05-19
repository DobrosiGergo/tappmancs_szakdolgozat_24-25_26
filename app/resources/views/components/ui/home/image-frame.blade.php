@props(['src' => '', 'alt' => ''])

<div class="flex justify-end">
  <x-ui.lazy-image
    src="{{ $src }}"
    alt="{{ $alt }}"
    class="w-full max-w-[640px] h-auto object-contain"
  />
</div>
