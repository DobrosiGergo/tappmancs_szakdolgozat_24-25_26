@props(['name'])
@php
    $path = resource_path("icons/{$name}.svg");
    $svg  = file_get_contents($path);
    $svg  = preg_replace('/<svg\b([^>]*)>/i', '<svg$1 ' . $attributes->toHtml() . '>', $svg, 1);
@endphp
{!! $svg !!}
