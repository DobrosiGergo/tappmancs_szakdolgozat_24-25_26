<x-app-layout>
    @php
        $pets = $shelter->pets()->latest()->get();
        $ownerName = $shelter->owner?->username ?? $shelter->owner?->name ?? 'Ismeretlen';
    @endphp

    <section class="relative overflow-hidden bg-white shadow-sm ring-1 ring-black/5">
        <div class="absolute inset-0 bg-[#333333]" style="clip-path: polygon(65% 0, 100% 0, 100% 100%, 35% 100%)"></div>

        <div class="relative z-10 flex items-center gap-6 px-8 py-10 sm:px-12 sm:py-14">
            <div class="shrink-0 grid h-20 w-20 place-items-center rounded-full bg-white shadow ring-1 ring-black/10">
                <img src="{{ asset('images/pet-shelter-svgrepo-com.svg') }}" alt="" class="h-11 w-11 opacity-80" />
            </div>

            <div class="min-w-0">
                <p class="mb-1 text-xs font-medium uppercase tracking-widest text-neutral-400">Állatmenhely</p>

                <h1 class="text-3xl font-bold tracking-tight text-neutral-900 sm:text-4xl md:text-5xl">
                    {{ $shelter->name }}
                </h1>

                <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1.5 text-sm text-neutral-500">
                    <span class="flex items-center gap-1.5">
                        <img src="{{ asset('images/profile.svg') }}" alt="" class="h-3.5 w-3.5 shrink-0">
                        {{ $ownerName }}
                    </span>

                    <span class="flex items-center gap-1.5">
                        <img src="{{ asset('images/calendar.svg') }}" alt="" class="h-3.5 w-3.5 shrink-0">
                        {{ $shelter->created_at->format('Y.m.d.') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-10xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="flex flex-col gap-6 lg:col-span-2">
                <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-black/5">
                    <h2 class="mb-4 text-xl font-semibold text-neutral-900">Leírás</h2>
                    <p class="whitespace-pre-line text-base leading-relaxed text-neutral-600">
                        {{ $shelter->description }}
                    </p>
                </div>

                <div class="rounded-2xl bg-neutral-100 p-4 ring-1 ring-black/5">
                    <livewire:shelter-gallery
                        :images="$shelter->images_safe"
                        :name="$shelter->name"
                    />
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <div class="rounded-2xl bg-[#333333] px-6 py-6 shadow-sm">
                    <p class="mb-4 text-xs font-medium uppercase tracking-widest text-neutral-400">Tulajdonos</p>

                    <div class="flex items-center gap-3">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-white/10 text-lg font-semibold text-white">
                            {{ strtoupper(substr($ownerName, 0, 1)) }}
                        </div>

                        <div>
                            <p class="font-medium text-white">{{ $ownerName }}</p>
                            <p class="text-xs text-neutral-400">Tag {{ $shelter->created_at->format('Y') }} óta</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white px-6 py-5 shadow-sm ring-1 ring-black/5">
                    <p class="mb-3 text-xs font-medium uppercase tracking-widest text-neutral-400">Részletek</p>

                    <dl class="divide-y divide-neutral-100">
                        <div class="flex justify-between py-2.5 text-sm">
                            <dt class="text-neutral-400">Frissítve</dt>
                            <dd class="font-medium text-neutral-800">{{ $shelter->updated_at->diffForHumans() }}</dd>
                        </div>

                        <div class="flex justify-between py-2.5 text-sm">
                            <dt class="text-neutral-400">Létrehozva</dt>
                            <dd class="font-medium text-neutral-800">{{ $shelter->created_at->format('Y.m.d.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <section class="mt-10 max-w-6xl">
            <div class="mb-5 flex items-baseline gap-3">
                <h2 class="text-2xl font-semibold text-neutral-900">Kisállatok</h2>

                @if($pets->count())
                    <span class="rounded-full bg-neutral-100 px-3 py-0.5 text-xs text-neutral-500">
                        {{ $pets->count() }} állat
                    </span>
                @endif
            </div>

            @if($pets->count())
                <div class="flex flex-col gap-3">
                    @foreach($pets as $pet)
                        @php
                            $images = $pet->images_safe;
                            $image = $images[0] ?? null;
                        @endphp

                        <a
                            href="{{ route('pets.show', $pet) }}"
                            class="group flex items-center gap-4 rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-black/5 transition hover:shadow-md hover:ring-black/10"
                        >
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-neutral-100">
                                @if($image)
                                    <img
                                        src="{{ asset('storage/' . $image) }}"
                                        alt="{{ $pet->name }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center">
                                        <img src="{{ asset('images/pet-placeholder.png') }}" alt="" class="h-12 w-12 object-contain opacity-70">
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

                            <img src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4 shrink-0 transition group-hover:translate-x-0.5">
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-neutral-200 bg-white px-8 py-12 text-center shadow-sm">
                    <p class="text-neutral-400">A menhely még nem töltött fel kisállatot.</p>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>