@props([
    'action',
    'placeholder' => 'Keresés...',
    'searchName'  => 'search',
])

<form method="GET" action="{{ $action }}">
    @foreach(request()->except([$searchName, 'page']) as $key => $value)
        @if($value !== '')
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach

    <div class="flex items-center gap-2 bg-white border border-neutral-200 rounded-full px-4 py-2.5 focus-within:border-neutral-900 focus-within:ring-2 focus-within:ring-neutral-900/10 transition">
        <svg class="shrink-0 w-4 h-4 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>
        </svg>
        <input
            type="text"
            name="{{ $searchName }}"
            value="{{ request($searchName) }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            class="flex-1 min-w-0 bg-transparent text-sm text-neutral-900 placeholder-neutral-400 border-0 p-0 outline-none focus:ring-0"
        />
        <button type="submit" class="shrink-0 text-neutral-400 hover:text-neutral-900 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14M13 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</form>
