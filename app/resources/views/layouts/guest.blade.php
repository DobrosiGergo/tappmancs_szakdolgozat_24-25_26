<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('pet-svgrepo-com.svg') }}" type="image/svg+xml">
        <link rel="shortcut icon" href="{{ asset('pet-svgrepo-com.svg') }}" type="image/svg+xml">
        <title>@yield('title', config('app.name', 'Tappmancs'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body >
    <x-navbar />
                @isset($header)
                    <header>
                            {{ $header }}
                    </header>
                @endisset
                <main >
                    {{ $slot }}
                </main>
    @livewireScripts
    </body>
</html>