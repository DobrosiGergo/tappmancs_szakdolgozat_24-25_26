<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="icon" href="{{ asset('pet-svgrepo-com.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('pet-svgrepo-com.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body class="min-h-screen antialiased bg-white">
    <x-navbar />

    @if (session('flash'))
      <x-flash-message
        :message="session('flash')['message']"
        :type="session('flash')['type'] ?? 'info'"
      />
    @endif

    @isset($header)
      <header class="px-6 lg:px-8 pt-6">
        {{ $header }}
      </header>
    @endisset

    <main class="px-6 lg:px-8 py-6">
      {{ $slot }}
    </main>

    @livewireScripts
  </body>
</html>
