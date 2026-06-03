<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tappmancs</title>
    <link rel="icon" href="{{ asset('images/pet-svgrepo-com.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen antialiased bg-white">
    <x-navbar />

    @if (session('flash'))
      <x-flash-message
        :message="session('flash')['message']"
        :type="session('flash')['type']"
      />
    @endif

    @isset($header)
      <header class="px-6 lg:px-8 pt-6">
        {{ $header }}
      </header>
    @endisset

    <main>
      {{ $slot }}
    </main>

    <x-ui.footer />
    @livewireScripts
  </body>
</html>
