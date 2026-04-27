@props(['href' => '#', 'label' => 'tappmancs'])
<a
  href="{{ $href }}"
  class="inline-flex items-center gap-2.5 group transition-colors duration-300"
  :class="open !== null ? 'text-white' : 'text-neutral-900'"
>
  <svg
    class="h-7 w-7 flex-shrink-0 transition-transform duration-300 group-hover:scale-110"
    viewBox="0 0 32 32"
    fill="currentColor"
    xmlns="http://www.w3.org/2000/svg"
  >
    <ellipse cx="16" cy="21"   rx="7"   ry="5.5"/>
    <circle  cx="8"  cy="15"   r="3"/>
    <circle  cx="13" cy="11"   r="2.8"/>
    <circle  cx="19" cy="11"   r="2.8"/>
    <circle  cx="24" cy="15"   r="3"/>
  </svg>
  <span class="font-semibold tracking-tight text-[15px]">{{ $label }}</span>
</a>
