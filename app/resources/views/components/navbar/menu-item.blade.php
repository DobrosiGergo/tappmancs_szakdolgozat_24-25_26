@props(['href' => '#', 'label' => ''])
<a
  href="{{ $href }}"
  {{ $attributes->merge(['class' => 'font-medium transition-colors duration-200']) }}
  :class="open ? 'text-white hover:text-white/80' : 'text-neutral-900 hover:text-[#2b2b2b]'"
>
  {{ $label }}
</a>
