<x-app-layout>

    <section class="relative overflow-hidden bg-[#333333]">

        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-white opacity-[0.03] blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-white opacity-[0.04] blur-2xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-6 py-16 sm:py-20 lg:px-8">

            <nav class="mb-10 flex items-center gap-2 text-xs font-medium" aria-label="Breadcrumb">
                <a href="{{ route('shelters.index') }}"
                   class="text-neutral-500 transition-colors hover:text-neutral-300">
                    Menhelyek
                </a>
                <x-icon name="arrow-right" class="h-3 w-3 opacity-50" />
                <a href="{{ route('shelters.show', $shelter) }}"
                   class="text-neutral-500 transition-colors hover:text-neutral-300">
                    {{ $shelter->name }}
                </a>
                <x-icon name="arrow-right" class="h-3 w-3 opacity-50" />
                <span class="text-neutral-400">Munkatársak</span>
            </nav>

            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                Munkatársak kezelése
            </h1>
            <p class="mt-4 text-neutral-400">
                Adj hozzá önkénteseket vagy távolíts el munkatársakat a <span class="text-neutral-200">{{ $shelter->name }}</span> menhely csapatából.
            </p>

        </div>
    </section>

    <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-4">

                <x-ui.card>
                    <div class="mb-5 flex items-baseline gap-3">
                        <h2 class="text-xl font-semibold text-neutral-900">Munkatársak</h2>
                        @if($workers->isNotEmpty())
                            <span class="rounded-full bg-neutral-100 px-3 py-0.5 text-xs text-neutral-500">
                                {{ $workers->count() }} fő
                            </span>
                        @endif
                    </div>

                    @if($workers->isNotEmpty())
                        <div class="flex flex-col divide-y divide-neutral-100">
                            @foreach($workers as $worker)
                                <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-neutral-100">
                                            <x-icon name="user" class="h-5 w-5 opacity-50" />
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-neutral-900">{{ $worker->name }}</p>
                                            <p class="text-xs text-neutral-400">{{ $worker->email }}</p>
                                        </div>
                                    </div>

                                    <form method="POST"
                                          action="{{ route('shelter.staffing.destroy', [$shelter, $worker]) }}"
                                          onsubmit="return confirm('Biztosan eltávolítod {{ $worker->name }} munkatársat?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-xs font-medium text-red-600 ring-1 ring-red-200 transition hover:bg-red-50">
                                            Eltávolítás
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-neutral-200 bg-neutral-50 px-8 py-12 text-center">
                            <x-icon name="user" class="mx-auto mb-3 h-8 w-8 opacity-30" />
                            <p class="text-sm text-neutral-400">Még nincs munkatárs hozzáadva.</p>
                        </div>
                    @endif
                </x-ui.card>

            </div>
            <div class="flex flex-col gap-4 lg:sticky lg:top-6 lg:self-start">

                <x-ui.card pad="px-6 py-6">
                    <h2 class="mb-5 text-base font-semibold text-neutral-900">Munkatárs hozzáadása</h2>

                    <form method="POST" action="{{ route('shelter.staffing.store', $shelter) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-xs font-medium text-neutral-500">
                                E-mail cím
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="pelda@email.com"
                                   class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-900/5"
                                   required>

                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full rounded-xl bg-[#333333] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-neutral-700">
                            Hozzáadás
                        </button>
                    </form>

                    <p class="mt-4 text-xs leading-relaxed text-neutral-400">
                        A felhasználónak már regisztrálva kell lennie a rendszerben. Email alapján keressük meg.
                    </p>
                </x-ui.card>

                <a href="{{ route('shelters.show', $shelter) }}"
                   class="flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-medium text-neutral-600 shadow-sm ring-1 ring-black/5 transition hover:bg-neutral-50">
                    <x-icon name="arrow-right" class="h-4 w-4 rotate-180 opacity-50" />
                    Vissza a menhely adatlapjára
                </a>

            </div>

        </div>
    </div>

</x-app-layout>
