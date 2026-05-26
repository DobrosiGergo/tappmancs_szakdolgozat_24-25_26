@props(['href' => '#', 'label' => 'tappmancs', 'dark' => false])
<a
  href="{{ $href }}"
  class="inline-flex items-center gap-2.5 group transition-colors duration-300"
  :class="(@json($dark) || open !== null) ? 'text-white' : 'text-neutral-900'"
>
  <x-icon name="paw" class="h-7 w-7 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" />
  <span class="font-semibold tracking-tight text-[15px]">{{ $label }}</span>
</a>
