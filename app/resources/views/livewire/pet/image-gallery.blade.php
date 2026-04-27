<div class="overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-sm">
    <div class="aspect-[16/10] bg-neutral-100">
        @if($selectedImage)
            <img
                src="{{ \Illuminate\Support\Facades\Storage::url($selectedImage) }}"
                alt="Kiválasztott kép"
                class="h-full w-full object-cover"
            >
        @else
            <div class="flex h-full items-center justify-center text-neutral-400">
                Nincs feltöltött kép
            </div>
        @endif
    </div>

    @if(count($images) > 1)
        <div class="grid grid-cols-2 gap-3 border-t border-neutral-200 p-4 sm:grid-cols-4">
            @foreach($images as $image)
                <button
                    type="button"
                    wire:click="selectImage('{{ $image }}')"
                    class="relative overflow-hidden rounded-2xl bg-neutral-100 aspect-[4/3] transition focus:outline-none focus:ring-2 focus:ring-neutral-900 {{ $selectedImage === $image ? 'ring-2 ring-neutral-900' : 'hover:opacity-90' }}"
                >
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($image) }}"
                        alt="Kisállat kép"
                        class="h-full w-full object-cover"
                    >
                </button>
            @endforeach
        </div>
    @endif
</div>