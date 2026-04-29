<x-app-layout>
    @php
        $pets      = $shelter->pets()->latest()->get();
        $ownerName = $shelter->owner?->username ?? $shelter->owner?->name ?? 'Ismeretlen';
        $petCount  = $pets->count();
    @endphp

    <section class="relative overflow-hidden bg-[#333333]">

        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-white opacity-[0.03] blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-white opacity-[0.04] blur-2xl"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-6 py-16 sm:py-20 lg:px-8">

            <nav class="animate-fade-up [animation-delay:0ms] mb-10 flex items-center gap-2 text-xs font-medium"
                 aria-label="Breadcrumb">
                <a href="{{ route('shelters.index') }}"
                   class="text-neutral-500 transition-colors hover:text-neutral-300">
                    Menhelyek
                </a>
                <img src="{{ asset('images/next.svg') }}" alt="" class="h-3 w-3 opacity-50">
                <span class="text-neutral-400">{{ $shelter->name }}</span>
            </nav>

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">

                <div class="max-w-3xl">
                    <p class="animate-fade-up [animation-delay:80ms] mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-neutral-500">
                        Állatmenhely
                    </p>

                    <h1 class="animate-fade-up [animation-delay:160ms] text-5xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl">
                        {{ $shelter->name }}
                    </h1>

                    <div class="animate-fade-up [animation-delay:280ms] mt-8 flex flex-wrap gap-3">
                        <x-ui.badge>
                            <img src="{{ asset('images/profile.svg') }}" alt="" class="h-3.5 w-3.5 opacity-70">
                            {{ $ownerName }}
                        </x-ui.badge>

                        <x-ui.badge>
                            <img src="{{ asset('images/calendar.svg') }}" alt="" class="h-3.5 w-3.5 opacity-70">
                            {{ $shelter->created_at->format('Y') }} óta aktív
                        </x-ui.badge>

                        @if($petCount > 0)
                            <x-ui.badge variant="emerald">
                                <img src="{{ asset('images/heart.svg') }}" alt="" class="h-3.5 w-3.5">
                                {{ $petCount }} kisállat
                            </x-ui.badge>
                        @endif
                    </div>
                </div>

                <div class="animate-fade-up [animation-delay:380ms] flex shrink-0 flex-wrap gap-3">
                    <a href="#contact"
                       class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-neutral-900 shadow-sm transition hover:bg-neutral-100">
                        Kapcsolatfelvétel
                        <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4">
                    </a>

                    @can('update', $shelter)
                        <a href="{{ route('shelter.edit', $shelter) }}"
                           class="inline-flex items-center gap-2 rounded-full bg-white/10 px-6 py-3 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/15">
                            <img src="{{ asset('images/pencil.svg') }}" alt="" class="h-4 w-4 brightness-0 invert">
                            Szerkesztés
                        </a>
                    @endcan
                </div>

            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

            <div class="space-y-8 lg:col-span-2">

                <x-ui.card data-reveal>
                    <h2 class="mb-4 text-xl font-semibold text-neutral-900">A menhelyről</h2>
                    <p class="whitespace-pre-line text-base leading-relaxed text-neutral-600">
                        {{ $shelter->description }}
                    </p>
                </x-ui.card>

                @if(!empty($shelter->images_safe))
                    <x-ui.card data-reveal pad="p-6">
                        <h2 class="mb-5 text-xl font-semibold text-neutral-900">Galéria</h2>
                        <x-ui.image-gallery :images="$shelter->images_safe" :alt="$shelter->name" />
                    </x-ui.card>
                @endif

                <x-ui.card data-reveal>
                    <div class="mb-5 flex items-baseline gap-3">
                        <h2 class="text-xl font-semibold text-neutral-900">Kisállatok</h2>
                        @if($petCount)
                            <span class="rounded-full bg-neutral-100 px-3 py-0.5 text-xs text-neutral-500">
                                {{ $petCount }} állat
                            </span>
                        @endif
                    </div>

                    @if($petCount)
                        <div class="flex flex-col gap-3">
                            @foreach($pets as $index => $pet)
                                @php
                                    $images = $pet->images_safe;
                                    $image  = $images[0] ?? null;
                                @endphp
                                <a href="{{ route('pets.show', $pet) }}"
                                   data-reveal
                                   style="transition-delay: {{ $index * 55 }}ms"
                                   class="group flex items-center gap-4 rounded-xl bg-neutral-50 px-5 py-4 ring-1 ring-black/5 transition hover:bg-white hover:shadow-md hover:ring-black/10">

                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-neutral-100">
                                        @if($image)
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="{{ $pet->name }}"
                                                 loading="lazy"
                                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center">
                                                <img src="{{ asset('images/pet-placeholder.png') }}"
                                                     alt=""
                                                     loading="lazy"
                                                     class="h-10 w-10 object-contain opacity-50">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-base font-semibold text-neutral-900">
                                            {{ $pet->name ?? 'Ismeretlen' }}
                                        </p>
                                        @if(!empty($pet->description))
                                            <p class="mt-0.5 truncate text-sm text-neutral-400">
                                                {{ $pet->description }}
                                            </p>
                                        @endif
                                    </div>

                                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-medium ring-1 {{ $pet->status_class }}">
                                        {{ $pet->status_label }}
                                    </span>

                                    <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4 shrink-0 opacity-60 transition group-hover:translate-x-0.5 group-hover:opacity-100">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-neutral-200 bg-neutral-50 px-8 py-12 text-center">
                            <img src="{{ asset('images/heart.svg') }}" alt="" class="mx-auto mb-3 h-8 w-8 opacity-30">
                            <p class="text-sm text-neutral-400">A menhely még nem töltött fel kisállatot.</p>
                        </div>
                    @endif
                </x-ui.card>

                <x-ui.contact-form />

            </div>

            <div class="flex flex-col gap-4 lg:sticky lg:top-6 lg:self-start">

                <div data-reveal class="rounded-2xl bg-[#333333] px-6 py-6 shadow-sm">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-widest text-neutral-500">
                        Tulajdonos
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full text-lg font-bold text-neutral-900"
                             style="background-color: {{ $shelter->avatar_bg }}">
                            {{ $shelter->initials }}
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ $ownerName }}</p>
                            <p class="text-xs text-neutral-400">
                                Tag {{ $shelter->created_at->format('Y') }} óta
                            </p>
                        </div>
                    </div>
                </div>

                <x-ui.card data-reveal style="transition-delay: 80ms" pad="px-6 py-5">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-neutral-400">
                        Részletek
                    </p>
                    <dl class="divide-y divide-neutral-100">
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <dt class="text-neutral-400">Frissítve</dt>
                            <dd class="font-medium text-neutral-800">
                                {{ $shelter->updated_at->diffForHumans() }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <dt class="text-neutral-400">Létrehozva</dt>
                            <dd class="font-medium text-neutral-800">
                                {{ $shelter->created_at->format('Y.m.d.') }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <dt class="text-neutral-400">Kisállatok</dt>
                            <dd class="font-medium text-neutral-800">{{ $petCount }} db</dd>
                        </div>
                    </dl>
                </x-ui.card>

                @unless(auth()->check() && in_array(auth()->user()->type, ['Shelterowner', 'Shelterworker']))
                    <a href="#contact"
                       data-reveal
                       style="transition-delay: 160ms"
                       class="flex items-center justify-center gap-2 rounded-2xl bg-[#333333] px-6 py-4 text-sm font-semibold text-white shadow-sm ring-1 ring-white/10 transition hover:bg-neutral-700">
                        <img src="{{ asset('images/mail.svg') }}" alt="" class="h-4 w-4 brightness-0 invert">
                        Kapcsolatfelvétel
                    </a>
                @endunless

            </div>

        </div>
    </div>

</x-app-layout>
