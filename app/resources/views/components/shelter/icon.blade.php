@props(['class' => 'h-8 w-8'])

<img src="{{ asset('images/pet.svg') }}" alt="" {{ $attributes->merge(['class' => $class]) }}>
