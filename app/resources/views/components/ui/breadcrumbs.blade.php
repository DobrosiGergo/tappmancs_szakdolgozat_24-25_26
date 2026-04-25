@props([
  'items' => [],
  'homeHref' => route('dashboard'),  
  'homeLabel' => '',
])

<nav aria-label="Breadcrumb">
  <ol class="flex items-center gap-2 text-sm text-neutral-500">
    <li>
      <a href="{{ $homeHref }}" class="inline-flex items-center gap-2 hover:text-neutral-700">
        <img src="{{ asset('images/home.svg') }}" alt="" class="h-4 w-4">
        <span>{{ $homeLabel }}</span>
      </a>
    </li>

    @foreach($items as $i => $item)
      <li class="flex items-center gap-2">
        <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4">

        @php $isLast = $i === count($items) - 1; @endphp

        @if(!empty($item['href']))
          <a href="{{ $item['href'] }}" 
             class="hover:text-neutral-700 {{ $isLast ? 'text-neutral-700 font-medium' : '' }}">
            {{ $item['label'] }}
          </a>
        @else
          <span class="text-neutral-700 font-medium">{{ $item['label'] }}</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>
