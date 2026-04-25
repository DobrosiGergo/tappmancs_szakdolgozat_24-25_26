@props(['class' => ''])
<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-0.5 rounded bg-blue-600 text-white text-xs font-semibold '.$class]) }}>
  {{ $slot }}
</span>
