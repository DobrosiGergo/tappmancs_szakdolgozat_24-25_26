@props(['message', 'type' => 'success'])

@php
    if ($type === 'success') {
        $barClass = 'bg-green-500';
    } elseif ($type === 'error') {
        $barClass = 'bg-red-500';
    } elseif ($type === 'warning') {
        $barClass = 'bg-yellow-400';
    } else {
        $barClass = 'bg-blue-500';
    }
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-3"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-3"
    class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-80 z-50 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/8"
>
    <div class="h-1 {{ $barClass }}"></div>
    <div class="flex items-center gap-3 px-4 py-3.5">
        <p class="flex-1 text-sm text-neutral-700">{{ $message }}</p>
        <button
            type="button"
            @click="show = false"
            aria-label="Bezárás"
            class="shrink-0 text-neutral-400 hover:text-neutral-600 transition-colors"
        >
            <x-icon name="delete" class="h-4 w-4" />
        </button>
    </div>
</div>
