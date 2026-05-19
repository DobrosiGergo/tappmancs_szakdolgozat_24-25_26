<footer class="border-t border-neutral-200 bg-white">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 pt-16 pb-8">
        <div class="flex flex-col gap-10 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-3xl font-semibold tracking-tight text-neutral-900 leading-tight">
                    Tappmancs
                </p>
                <p class="mt-4 text-sm text-neutral-500 leading-relaxed max-w-xs">
                    Egy közösség, amely menhelyeket és leendő gazdikat köt össze egyetlen helyen.
                </p>
            </div>

            <div class="flex gap-16">
                <div>
                    <h4 class="font-mono text-[11px] uppercase tracking-widest text-neutral-400 mb-4">Felfedezés</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('pets.index') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Kisállatok</a></li>
                        <li><a href="{{ route('shelters.index') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Menhelyek</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-mono text-[11px] uppercase tracking-widest text-neutral-400 mb-4">Fiók</h4>
                    <ul class="space-y-2">
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Irányítópult</a></li>
                            <li><a href="{{ route('settings.index') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Beállítások</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Bejelentkezés</a></li>
                            <li><a href="{{ route('register') }}" class="text-sm text-neutral-700 hover:text-neutral-900 transition-colors">Regisztráció</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-neutral-100 pt-6">
            <span class="font-mono text-xs text-neutral-400">© {{ date('Y') }} Tappmancs</span>
        </div>
    </div>
</footer>
