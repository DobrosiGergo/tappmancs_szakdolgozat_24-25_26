<header {{ $attributes->merge(['class' => 'sticky top-0 z-50']) }}>
  <div class="relative" @mouseleave="open = null">
    <div
      class="shadow-sm transition-colors duration-200"
      :class="open ? 'bg-[#2b2b2b] text-white' : 'bg-white text-neutral-900'"
    >
      <div class="grid grid-cols-12 gap-8 items-center h-16 px-6 lg:px-8 transition-colors duration-200">
        {{ $slot }}
      </div>
    </div>
    {{ $dropdown ?? '' }}
  </div>
</header>
