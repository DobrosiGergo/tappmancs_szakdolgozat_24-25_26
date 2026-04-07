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
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a5 5 0 1 0-5-5 5.006 5.006 0 0 0 5 5Zm0 2c-3.33 0-10 1.667-10 5v1h20v-1c0-3.333-6.67-5-10-5Z"/>
                        </svg>
                        {{ $ownerName }}
                    </span>

                    <span class="flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 16H5V10h14ZM5 8V6h14v2Z"/>
                        </svg>
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
                            $images = $pet->images_safe ?? (is_array($pet->images) ? $pet->images : json_decode($pet->images ?? '[]', true));
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
                                        <svg class="h-6 w-6 text-neutral-300" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z"/>
                                        </svg>
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

                            <svg
                                class="h-4 w-4 shrink-0 text-neutral-300 transition group-hover:translate-x-0.5 group-hover:text-neutral-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
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