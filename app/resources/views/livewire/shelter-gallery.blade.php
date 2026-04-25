<div class="space-y-4">
@php $selectedImage = $images[$currentIndex] ?? null; @endphp
    @if($selectedImage)
        <div class="overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-sm">
            <div class="grid items-start gap-4 p-4 lg:grid-cols-[minmax(0,1fr)_180px]">
                <div class="relative overflow-hidden rounded-3xl border border-neutral-200 bg-neutral-100">
                    <button
                        type="button"
                        wire:click="openLightbox({{ $currentIndex }})"
                        class="block h-full w-full text-left"
                        aria-label="Kép megnyitása nagy méretben"
                    >
                        <div class="relative flex min-h-[320px] items-center justify-center bg-neutral-100 p-4 sm:min-h-[380px] lg:h-[560px]">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($selectedImage) }}"
                                alt="{{ $name }} képe"
                                class="max-h-full max-w-full object-contain transition duration-300 hover:scale-[1.01]"
                            >

                            <div class="pointer-events-none absolute inset-x-0 bottom-0 z-20 bg-gradient-to-t from-black/45 via-black/10 to-transparent p-5">
                                <div class="flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $name }}</p>
                                        <p class="text-xs text-white/85">Kattints a nagyításhoz</p>
                                    </div>

                                    <div class="rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-neutral-700 shadow-sm">
                                        {{ $currentIndex + 1 }} / {{ count($images) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </button>

                    @if(count($images) > 1)
                        <button
                            type="button"
                            wire:click="previousImage"
                            class="absolute left-4 top-1/2 z-20 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-neutral-200 bg-white/90 text-neutral-800 shadow transition hover:bg-white"
                            aria-label="Előző kép"
                        >
                            <img src="{{ asset('images/prev.svg') }}" alt="Előző" class="h-5 w-5 object-contain">
                        </button>

                        <button
                            type="button"
                            wire:click="nextImage"
                            class="absolute right-4 top-1/2 z-20 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full border border-neutral-200 bg-white/90 text-neutral-800 shadow transition hover:bg-white"
                            aria-label="Következő kép"
                        >
                            <img src="{{ asset('images/next.svg') }}" alt="Következő" class="h-5 w-5 object-contain">
                        </button>
                    @endif
                </div>

                @if(count($images) > 1)
                    <div class="lg:h-[560px] lg:overflow-y-auto lg:pr-1">
                        <div class="grid grid-cols-3 gap-3 lg:grid-cols-1">
                            @foreach($images as $index => $image)
                                <button
                                    type="button"
                                    wire:click="selectImage({{ $index }})"
                                    class="relative overflow-hidden rounded-2xl border bg-neutral-50 transition focus:outline-none focus:ring-2 focus:ring-neutral-400 {{ $currentIndex === $index ? 'border-neutral-300 ring-2 ring-neutral-300 shadow-sm' : 'border-neutral-200 hover:border-neutral-300 hover:bg-white' }}"
                                    aria-label="Kép kiválasztása"
                                >
                                    <div class="flex aspect-[4/3] items-center justify-center bg-neutral-100 p-2">
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"
                                            alt="{{ $name }} bélyegkép"
                                            class="h-full w-full object-contain transition duration-300"
                                        >
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="flex min-h-[340px] items-center justify-center rounded-3xl border border-neutral-200 bg-white shadow-sm">
            <div class="text-center">
                <p class="text-sm font-semibold text-neutral-500">Még nincs feltöltött kép</p>
                <p class="mt-1 text-xs text-neutral-400">A menhely galériája itt fog megjelenni.</p>
            </div>
        </div>
    @endif

    @if($isLightboxOpen && $selectedImage)
        <div
            class="fixed inset-0 z-[999] flex items-center justify-center bg-white/20 p-4 backdrop-blur-sm"
            wire:click.self="closeLightbox"
        >
            <div class="relative w-full max-w-6xl overflow-hidden rounded-[32px] border border-neutral-200 bg-white/95 shadow-2xl">
                <button
                    type="button"
                    wire:click="closeLightbox"
                    class="absolute right-4 top-4 z-30 grid h-11 w-11 place-items-center rounded-full border border-neutral-200 bg-white/90 text-neutral-700 shadow-sm transition hover:bg-white"
                    aria-label="Bezárás"
                >
                    <img src="{{ asset('images/delete.svg') }}" alt="Bezárás" class="h-5 w-5 object-contain">
                </button>

                <div class="grid min-h-[70vh] gap-4 bg-white/95 p-4 lg:grid-cols-[minmax(0,1fr)_210px]">
                    <div class="relative flex items-center justify-center rounded-[24px] border border-neutral-200 bg-neutral-100 p-4">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($selectedImage) }}"
                            alt="{{ $name }} kép"
                            class="max-h-[78vh] max-w-full object-contain transition duration-200"
                        >

                        @if(count($images) > 1)
                            <button
                                type="button"
                                wire:click="previousImage"
                                class="absolute left-4 top-1/2 z-20 grid h-12 w-12 -translate-y-1/2 place-items-center rounded-full border border-neutral-200 bg-white/90 text-neutral-700 shadow-sm transition hover:bg-white"
                                aria-label="Előző kép"
                            >
                                <img src="{{ asset('images/prev.svg') }}" alt="Előző" class="h-5 w-5 object-contain">
                            </button>

                            <button
                                type="button"
                                wire:click="nextImage"
                                class="absolute right-4 top-1/2 z-20 grid h-12 w-12 -translate-y-1/2 place-items-center rounded-full border border-neutral-200 bg-white/90 text-neutral-700 shadow-sm transition hover:bg-white"
                                aria-label="Következő kép"
                            >
                                <img src="{{ asset('images/next.svg') }}" alt="Következő" class="h-5 w-5 object-contain">
                            </button>
                        @endif

                        <div class="absolute bottom-4 left-1/2 z-20 -translate-x-1/2 rounded-full border border-neutral-200 bg-white/90 px-4 py-1.5 text-sm text-neutral-700 shadow-sm">
                            {{ $currentIndex + 1 }} / {{ count($images) }}
                        </div>
                    </div>

                    @if(count($images) > 1)
                        <div class="max-h-[78vh] overflow-y-auto pr-1">
                            <div class="grid grid-cols-3 gap-3 lg:grid-cols-1">
                                @foreach($images as $index => $image)
                                    <button
                                        type="button"
                                        wire:click="selectImage({{ $index }})"
                                        class="relative overflow-hidden rounded-3xl border bg-neutral-50 transition {{ $currentIndex === $index ? 'border-neutral-300 ring-2 ring-neutral-300 shadow-sm' : 'border-neutral-200 hover:border-neutral-300 hover:bg-white' }}"
                                        aria-label="Kép kiválasztása lightboxban"
                                    >
                                        <div class="flex aspect-[4/3] items-center justify-center bg-neutral-100 p-2">
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"
                                                alt="{{ $name }} bélyegkép"
                                                class="h-full w-full object-contain transition duration-300"
                                            >
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>