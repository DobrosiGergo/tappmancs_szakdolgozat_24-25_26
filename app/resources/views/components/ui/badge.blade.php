@props(['variant' => 'default'])

@php
$variants = [
    'default' => 'bg-white/10 text-neutral-300 ring-white/10',
    'emerald' => 'bg-emerald-400/10 text-emerald-400 ring-emerald-400/20',
];
$color = $variants[$variant] ?? $variants['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-full {$color} px-4 py-2 text-sm ring-1"]) }}>
    {{ $slot }}
</span>
