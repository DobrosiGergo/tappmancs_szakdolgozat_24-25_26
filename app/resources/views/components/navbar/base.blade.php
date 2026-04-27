<header {{ $attributes->merge(['class' => 'sticky top-0 z-50']) }}>
  <div class="relative" @mouseleave="open = null">
    <div
      class="transition-colors duration-300"
      :class="open !== null ? 'bg-neutral-900 border-b border-white/10 shadow-none' : 'bg-white border-b border-neutral-100 shadow-sm'"
    >
      <div class="grid grid-cols-12 gap-8 items-center h-16 px-6 lg:px-8">
        {{ $slot }}
      </div>
    </div>
    {{ $dropdown ?? '' }}
  </div>
</header>
