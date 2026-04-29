@props(['images' => [], 'alt' => ''])

@php
    use Illuminate\Support\Facades\Storage;

    $urls = collect($images)
        ->filter()
        ->map(fn ($img) => Storage::url($img))
        ->values()
        ->toArray();
@endphp

@if (empty($urls))
    <div class="flex min-h-[260px] items-center justify-center rounded-2xl border border-dashed border-neutral-200 bg-neutral-50">
        <div class="text-center">
            <img src="{{ asset('images/pet-placeholder.png') }}" alt="" class="mx-auto mb-3 h-10 w-10 object-contain opacity-40">
            <p class="text-sm font-medium text-neutral-400">Még nincs feltöltött kép</p>
        </div>
    </div>
@else
    <div
        x-data="{
            urls: @js($urls),
            alt: @js($alt),
            current: 0,
            lightbox: false,
            get url() { return this.urls[this.current] ?? ''; },
            get total() { return this.urls.length; },
            get hasMany() { return this.total > 1; },
            prev() { this.current = (this.current - 1 + this.total) % this.total; },
            next() { this.current = (this.current + 1) % this.total; },
            select(i) { this.current = i; },
            open(i) { this.current = i; this.lightbox = true; },
            close() { this.lightbox = false; }
        }"
        x-effect="document.body.style.overflow = lightbox ? 'hidden' : ''"
        @keydown.escape.window="close()"
        @keydown.arrow-left.window="lightbox && (prev(), $event.preventDefault())"
        @keydown.arrow-right.window="lightbox && (next(), $event.preventDefault())"
        class="space-y-3"
    >
        <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
            <div class="group relative">
                <div
                    class="aspect-[4/3] w-full overflow-hidden bg-neutral-100 sm:aspect-[16/10]"
                    role="button"
                    tabindex="0"
                    @click="open(current)"
                    @keydown.enter.prevent="open(current)"
                    @keydown.space.prevent="open(current)"
                >
                    <img
                        src="{{ $urls[0] }}"
                        :src="url"
                        :alt="alt"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.02]"
                        loading="eager"
                        decoding="async"
                    >
                </div>

                <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/45 to-transparent px-4 pb-4 pt-10">
                    <div class="flex items-end justify-between gap-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white/95" x-text="alt || 'Kép'"></p>
                            <p class="text-xs text-white/70">
                                <span x-text="current + 1"></span>
                                <span x-show="hasMany"> / <span x-text="total"></span></span>
                            </p>
                        </div>

                        <button
                            type="button"
                            @click.stop="open(current)"
                            class="pointer-events-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-neutral-900 shadow-sm transition hover:bg-white"
                            aria-label="Nagyítás"
                        >
                            <img src="{{ asset('images/search.svg') }}" alt="" class="h-4 w-4">
                        </button>
                    </div>
                </div>

                <button
                    x-show="hasMany"
                    type="button"
                    @click.stop="prev()"
                    aria-label="Előző kép"
                    class="absolute left-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-neutral-900 shadow-sm transition hover:bg-white"
                >
                    <img src="{{ asset('images/prev.svg') }}" alt="" class="h-5 w-5">
                </button>

                <button
                    x-show="hasMany"
                    type="button"
                    @click.stop="next()"
                    aria-label="Következő kép"
                    class="absolute right-3 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-neutral-900 shadow-sm transition hover:bg-white"
                >
                    <img src="{{ asset('images/next.svg') }}" alt="" class="h-5 w-5">
                </button>
            </div>
        </div>

        <div
            x-show="hasMany"
            class="flex gap-2 overflow-x-auto pb-1"
            style="scrollbar-width: none; -ms-overflow-style: none;"
        >
            <template x-for="(thumbUrl, i) in urls" :key="i">
                <button
                    type="button"
                    @click="select(i)"
                    :aria-label="`${i + 1}. kép`"
                    :aria-current="i === current ? 'true' : 'false'"
                    class="relative h-20 w-24 flex-none overflow-hidden rounded-xl border bg-white shadow-sm transition sm:h-24 sm:w-32"
                    :class="i === current
                        ? 'border-neutral-900 ring-1 ring-neutral-900'
                        : 'border-neutral-200 opacity-75 hover:border-neutral-300 hover:opacity-100'"
                >
                    <img
                        :src="thumbUrl"
                        :alt="`${alt} ${i + 1}`"
                        class="h-full w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                </button>
            </template>
        </div>

        <template x-teleport="body">
            <div
                x-show="lightbox"
                x-cloak
                x-transition.opacity
                @click.self="close()"
                class="fixed inset-0 z-[9999] backdrop-blur px-4 py-4 sm:px-6 sm:py-6"
            >
                <div class="mx-auto flex h-full max-w-6xl flex-col">
                    <div class="mb-4 flex items-center justify-between gap-4 text-white">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium" x-text="alt || 'Képnézegető'"></p>
                            <p class="text-xs text-white/65">
                                <span x-text="current + 1"></span> / <span x-text="total"></span>
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="close()"
                            aria-label="Bezárás"
                            class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
                        >
                            <img src="{{ asset('images/delete.svg') }}" alt="" class="h-4 w-4 brightness-0 invert">
                        </button>
                    </div>

                    <div class="relative flex min-h-0 flex-1 items-center justify-center">
                        <img
                            :src="url"
                            :alt="alt"
                            class="max-h-full w-full rounded-2xl object-contain"
                            decoding="async"
                        >

                        <button
                            x-show="hasMany"
                            type="button"
                            @click="prev()"
                            aria-label="Előző kép"
                            class="absolute left-0 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-neutral-900 shadow-sm transition hover:bg-white sm:left-3"
                        >
                            <img src="{{ asset('images/prev.svg') }}" alt="" class="h-5 w-5">
                        </button>

                        <button
                            x-show="hasMany"
                            type="button"
                            @click="next()"
                            aria-label="Következő kép"
                            class="absolute right-0 top-1/2 z-10 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-neutral-900 shadow-sm transition hover:bg-white sm:right-3"
                        >
                            <img src="{{ asset('images/next.svg') }}" alt="" class="h-5 w-5">
                        </button>
                    </div>

                    <div
                        x-show="hasMany"
                        class="mt-4 flex gap-2 overflow-x-auto pb-1"
                        style="scrollbar-width: none; -ms-overflow-style: none;"
                    >
                        <template x-for="(thumbUrl, i) in urls" :key="i">
                            <button
                                type="button"
                                @click="select(i)"
                                :aria-label="`${i + 1}. kép`"
                                class="h-16 w-20 flex-none overflow-hidden rounded-lg border transition sm:h-20 sm:w-28"
                                :class="i === current
                                    ? 'border-white ring-1 ring-white'
                                    : 'border-white/20 opacity-60 hover:opacity-100'"
                            >
                                <img
                                    :src="thumbUrl"
                                    :alt="`${alt} ${i + 1}`"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endif
