@props(['pad' => 'p-8'])

<div {{ $attributes->merge(['class' => "rounded-2xl bg-white {$pad} shadow-sm ring-1 ring-black/5"]) }}>
    {{ $slot }}
</div>
