@if ($paginator->hasPages())
    <nav aria-label="Lapozás" class="flex items-center justify-center gap-1">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 opacity-30 cursor-not-allowed select-none">
                <img src="{{ asset('images/prev.svg') }}" alt="" class="w-4 h-4">
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 hover:border-neutral-900 transition">
                <img src="{{ asset('images/prev.svg') }}" alt="" class="w-4 h-4">
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-neutral-400 select-none">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-neutral-900 text-white text-sm font-medium select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 text-neutral-600 text-sm hover:border-neutral-900 hover:text-neutral-900 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 hover:border-neutral-900 transition">
                <img src="{{ asset('images/next.svg') }}" alt="" class="w-4 h-4">
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 opacity-30 cursor-not-allowed select-none">
                <img src="{{ asset('images/next.svg') }}" alt="" class="w-4 h-4">
            </span>
        @endif

    </nav>
@endif
