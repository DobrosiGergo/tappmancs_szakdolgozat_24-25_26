@props(['href' => '#', 'label' => 'tappmancs'])
<a href="{{ $href }}" class="inline-flex items-center">
  <img src="{{ asset('images/pet.svg') }}" alt="" class="h-7 w-7">
  <span class="ml-2 font-medium transition-colors" :class="open ? 'text-white' : 'text-neutral-900'">{{ $label }}</span>
</a>
