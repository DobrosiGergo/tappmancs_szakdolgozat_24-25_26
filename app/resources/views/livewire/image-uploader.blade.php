<div>
    @php
        if ($max > 0) {
            $barWidth = min(100, (int) round($used / $max * 100));
        } else {
            $barWidth = 0;
        }
    @endphp
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-neutral-400">Feltöltött képek</span>
        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
            {{ $used }} / {{ $max }}
        </span>
    </div>

    <div class="h-1 w-full rounded-full bg-neutral-100 mb-4 overflow-hidden">
        <div
            class="h-full rounded-full transition-all duration-300 {{ $barClass }}"
            style="width: {{ $barWidth }}%"
        ></div>
    </div>

    <label
        for="imageUpload-{{ $uid }}"
        @class([
            'flex w-full flex-col items-center justify-center rounded-2xl border-2 border-dashed p-6 text-center text-sm transition',
            'cursor-pointer border-neutral-300 text-neutral-500 hover:bg-neutral-50' => ! $isAtMax,
            'cursor-not-allowed border-neutral-200 bg-neutral-50 text-neutral-400'   => $isAtMax,
        ])
    >
        @if ($isAtMax)
            <x-icon name="no-entry" class="mb-2 h-8 w-8" />
            <span class="font-medium">Elérted a képfeltöltési határt</span>
            <span class="mt-1 text-xs text-neutral-400">Törölj egy képet az újabb feltöltéséhez</span>
        @else
            <x-icon name="upload" class="mb-2 h-10 w-10" />
            <span class="font-medium">Válassza ki képeit</span>
            <span class="mt-1 text-xs text-neutral-400">JPEG, JPG, PNG · max {{ $fileSizeLabel }} / kép</span>
            @if ($isNearMax)
                <span class="mt-2 inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-600 ring-1 ring-inset ring-amber-200">
                    Még {{ $max - $used }} szabad hely
                </span>
            @endif
        @endif

        <input
            type="file"
            multiple
            id="imageUpload-{{ $uid }}"
            class="hidden"
            @disabled($isAtMax)
            @change="$wire.uploadMultiple('images', $event.target.files); $event.target.value = ''"
        >
    </label>

    @if (! empty($existingImages))
        <div class="mt-6">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-neutral-400">Jelenlegi képek</h3>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ($existingImages as $index => $image)
                    <div class="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100">
                        <x-ui.lazy-image src="{{ Storage::disk('public')->url($image['path']) }}" alt="" class="h-28 w-full object-cover" />
                        <button
                            type="button"
                            wire:click="removeExistingImage({{ $index }})"
                            class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-white opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition"
                        >
                            <x-icon name="delete" class="h-3.5 w-3.5" />
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($previews)
        <div class="mt-6">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-neutral-400">Új képek</h3>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ($previews as $index => $image)
                    <div class="group relative overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-100">
                        <x-ui.lazy-image src="{{ Storage::disk('public')->url($image['path']) }}" alt="" class="h-28 w-full object-cover" />
                        <button
                            type="button"
                            wire:click="removeImage({{ $index }})"
                            class="absolute right-2 top-2 flex h-7 w-7 items-center justify-center rounded-full bg-black/60 text-white opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition"
                        >
                            <x-icon name="delete" class="h-3.5 w-3.5" />
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @error('images')
        <p class="mt-3 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
