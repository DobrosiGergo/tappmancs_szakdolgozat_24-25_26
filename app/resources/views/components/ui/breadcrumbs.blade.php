@props([
  'items' => [],
  'homeHref' => auth()->check() ? route('dashboard') : route('home'),
  'homeLabel' => '',
  'dark' => false,
])

@php
  if ($dark) {
      $base = 'text-white/50';
      $hover = 'hover:text-white';
      $current = 'text-white';
      $separator = 'text-white/30';
  } else {
      $base = 'text-neutral-500';
      $hover = 'hover:text-neutral-900';
      $current = 'text-neutral-900';
      $separator = 'text-neutral-300';
  }
@endphp

<nav aria-label="Breadcrumb" {{ $attributes }}>
  <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 text-sm leading-5 {{ $base }}">
    <li class="flex items-center">
      <a href="{{ $homeHref }}" class="flex items-center gap-1.5 transition-colors {{ $hover }}">
        <x-icon name="home" class="h-4 w-4 shrink-0" />
        @if($homeLabel)
          <span>{{ $homeLabel }}</span>
        @endif
      </a>
    </li>

    @foreach($items as $i => $item)
      @php $isLast = $i === count($items) - 1; @endphp
      <li class="flex items-center gap-2">
        <span aria-hidden="true" class="{{ $separator }}">/</span>
        @if($isLast)
          <span class="font-medium {{ $current }}" aria-current="page">{{ $item['label'] }}</span>
        @elseif(!empty($item['href']))
          <a href="{{ $item['href'] }}" class="transition-colors {{ $hover }}">{{ $item['label'] }}</a>
        @else
          <span>{{ $item['label'] }}</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
