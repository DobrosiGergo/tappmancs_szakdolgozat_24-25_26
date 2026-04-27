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
      <svg class="h-7 w-7 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="16" cy="21" rx="7" ry="5.5"/>
        <circle cx="8"  cy="15" r="3"/>
        <circle cx="13" cy="11" r="2.8"/>
        <circle cx="19" cy="11" r="2.8"/>
        <circle cx="24" cy="15" r="3"/>
      </svg>
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
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
          </svg>
          Vissza
        </button>
        <a
          href="/"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-neutral-900 text-sm font-medium text-white hover:bg-neutral-700 transition-colors duration-150"
        >
          Főoldal
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
          </svg>
        </a>
      </div>

    </div>
  </div>

</body>
</html>
