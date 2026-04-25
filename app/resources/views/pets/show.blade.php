<x-app-layout>
    @php
        $images = $pet->images_safe ?? [];

        $statusLabel = match($pet->status) {
            'adopted' => 'Örökbefogadott',
            'reserved' => 'Foglalva',
            default => 'Elérhető',
        };

        $statusClasses = match($pet->status) {
            'adopted' => 'bg-neutral-900 text-white',
            'reserved' => 'bg-amber-100 text-amber-800 ring-1 ring-amber-200',
            default => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200',
        };
    @endphp

    <div class="py-10">
        <x-ui.container>
            <div class="mb-6">
                <x-ui.breadcrumbs
                    homeHref="{{ route('home') }}"
                    homeLabel="Főoldal"
                    :items="[
                        ['href' => route('pets.index'), 'label' => 'Kisállatok'],
                        ['label' => $pet->name],
                    ]"
                />
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <livewire:pet.image-gallery :images="$images" />

                    <div class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex flex-wrap items-center gap-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>

                            @if($pet->arrival_date)
                                <span class="text-sm text-neutral-500">
                                    Beérkezés: {{ $pet->arrival_date->format('Y.m.d.') }}
                                </span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">
                            {{ $pet->name }}
                        </h1>

                        <p class="mt-4 text-base leading-7 text-neutral-700">
                            {{ $pet->description }}
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-neutral-900">Alapadatok</h2>

                        <dl class="mt-5 space-y-4">
                            <div class="flex items-start justify-between gap-4 border-b border-neutral-100 pb-4">
                                <dt class="text-sm text-neutral-500">Faj</dt>
                                <dd class="text-right text-sm font-medium text-neutral-900">
                                    {{ $pet->species?->name ?? 'Nincs megadva' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4 border-b border-neutral-100 pb-4">
                                <dt class="text-sm text-neutral-500">Fajta</dt>
                                <dd class="text-right text-sm font-medium text-neutral-900">
                                    {{ $pet->breed?->name ?? 'Nincs megadva' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4 border-b border-neutral-100 pb-4">
                                <dt class="text-sm text-neutral-500">Kor</dt>
                                <dd class="text-right text-sm font-medium text-neutral-900">
                                    {{ isset($pet->age) ? $pet->age . ' év' : 'Nincs megadva' }}
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4 border-b border-neutral-100 pb-4">
                                <dt class="text-sm text-neutral-500">Menhely</dt>
                                <dd class="text-right text-sm font-medium text-neutral-900">
                                    @if($pet->shelter)
                                        <a
                                            href="{{ route('shelters.show', $pet->shelter) }}"
                                            class="underline underline-offset-4 transition hover:text-black"
                                        >
                                            {{ $pet->shelter->name }}
                                        </a>
                                    @else
                                        Nincs hozzárendelve
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-sm text-neutral-500">Feltöltötte</dt>
                                <dd class="text-right text-sm font-medium text-neutral-900">
                                    {{ $pet->employee?->username ?? $pet->employee?->name ?? 'Ismeretlen' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-neutral-900">Érdekel az örökbefogadás?</h2>
                        <p class="mt-3 text-sm leading-6 text-neutral-600">
                            Vedd fel a kapcsolatot a menhellyel, és érdeklődj a kisállat örökbefogadásának menetéről.
                        </p>

                        @if($pet->shelter)
                            <a
                                href="{{ route('shelters.show', $pet->shelter) }}"
                                class="mt-5 inline-flex items-center justify-center rounded-2xl bg-neutral-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-black"
                            >
                                Menhely megtekintése
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($relatedPets->count())
                <div class="mt-10">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold text-neutral-900">További kisállatok ettől a menhelytől</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($relatedPets as $relatedPet)
                            @php
                                $desc = \Illuminate\Support\Str::limit($relatedPet->description ?? '', 120);
                                $image = $relatedPet->images_safe[0] ?? null;
                                $badge = match($relatedPet->status) {
                                    'adopted' => 'Örökbefogadott',
                                    'reserved' => 'Foglalva',
                                    default => 'Elérhető',
                                };
                                $meta = [
                                    'Faj' => $relatedPet->species?->name,
                                    'Fajta' => $relatedPet->breed?->name,
                                    'Kor' => isset($relatedPet->age) ? ($relatedPet->age . ' év') : null,
                                ];
                            @endphp

                            <x-pet.card
                                :href="route('pets.show', $relatedPet)"
                                :title="$relatedPet->name"
                                :description="$desc"
                                :image="$image"
                                :badge="$badge"
                                :shelterName="$relatedPet->shelter?->name"
                                :meta="$meta"
                            />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $relatedPets->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </x-ui.container>
    </div>
</x-app-layout>