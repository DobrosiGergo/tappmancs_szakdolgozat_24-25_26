@props(['dark' => false])
<header {{ $attributes->merge(['class' => 'sticky top-0 z-50']) }}>
  <div class="relative" @mouseleave="open = null" @keydown.escape.window="open = null">
    <div
      class="transition-colors duration-300"
      :class="{ 'bg-neutral-900 shadow-none': @json($dark) || open !== null, 'bg-white border-b border-neutral-100 shadow-sm': !@json($dark) && open === null }"
    >
      <div class="grid grid-cols-12 gap-x-2 md:gap-x-8 items-center h-16 px-6 lg:px-8">
        {{ $slot }}
      </div>
    </div>
    @isset($dropdown){{ $dropdown }}@endisset
  </div>
</header>
