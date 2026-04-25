@props(['message', 'type' => 'success'])

@php
    $typeClasses = [
        'success' => 'bg-green-500 text-white',
        'error' => 'bg-red-500 text-white',
        'warning' => 'bg-yellow-400 text-black',
        'info' => 'bg-blue-500 text-white',
    ];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    @click="show = false"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-fit max-w-md px-4 py-2 rounded-xl shadow-lg text-sm font-medium transition-all duration-300 {{ $typeClasses[$type] ?? $typeClasses['info'] }}"
>
    {{ $message }}
</div>
