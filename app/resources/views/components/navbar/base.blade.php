@props(['dark' => false])
<header {{ $attributes->merge(['class' => 'sticky top-0 z-50']) }}>
  <div class="relative" @mouseleave="leave()" @mouseenter="stay()" @keydown.escape.window="open = null">
    <div
      class="border-b transition-colors"
      :class="{ 'bg-neutral-900 border-neutral-900 shadow-none duration-75': @json($dark) || shaded, 'bg-white border-neutral-100 shadow-sm duration-200': !@json($dark) && !shaded }"
    >
      <div class="grid grid-cols-12 gap-x-2 md:gap-x-8 items-center h-16 px-6 lg:px-8">
        {{ $slot }}
      </div>
    </div>
    @isset($dropdown){{ $dropdown }}@endisset
  </div>
</header>
