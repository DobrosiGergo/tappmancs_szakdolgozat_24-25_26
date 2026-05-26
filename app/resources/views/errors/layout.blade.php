<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('code', 'Hiba') – Tappmancs</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex flex-col antialiased">

  <div class="border-b border-neutral-100 px-6 lg:px-8 h-16 flex items-center">
    <a href="/" class="inline-flex items-center gap-2.5 text-neutral-900 group">
      <x-icon name="paw" class="h-7 w-7 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" />
      <span class="font-semibold tracking-tight text-[15px]">tappmancs</span>
    </a>
  </div>

  <div class="flex-1 flex items-center justify-center px-6 py-20">
    <div class="text-center max-w-lg w-full">

      <div class="text-[clamp(80px,20vw,160px)] font-bold leading-none text-neutral-100 select-none tracking-tight">
        @yield('code')
      </div>

      <h1 class="text-2xl md:text-3xl font-semibold text-neutral-900 -mt-4 mb-3">
        @yield('title')
      </h1>

      <p class="text-neutral-500 text-sm leading-relaxed mb-10 max-w-sm mx-auto">
        @yield('description')
      </p>

      <div class="flex items-center justify-center gap-3 flex-wrap">
        <button
          onclick="history.back()"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-neutral-200 text-sm font-medium text-neutral-700 hover:border-neutral-400 hover:text-neutral-900 transition-colors duration-150"
        >
          <x-icon name="arrow-left" class="w-4 h-4" />
          Vissza
        </button>
        <a
          href="/"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-neutral-900 text-sm font-medium text-white hover:bg-neutral-700 transition-colors duration-150"
        >
          Főoldal
          <x-icon name="arrow-right" class="w-4 h-4" />
        </a>
      </div>

    </div>
  </div>

</body>
</html>
