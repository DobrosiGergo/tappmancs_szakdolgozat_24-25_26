<x-app-layout>
  <style>[x-cloak]{display:none!important}</style>

  <section class="mb-10">
    <div class="relative overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-black/5">
      <div class="absolute inset-0 bg-white"></div>
      <div class="absolute inset-0 bg-[#333333]" style="clip-path: polygon(70% 0, 100% 0, 100% 100%, 40% 100%);"></div>

      <div class="relative z-10 flex items-start gap-5 p-6 sm:p-10">
        <div class="shrink-0">
          <div class="grid h-16 w-16 place-items-center rounded-full bg-neutral-100 shadow-sm ring-1 ring-black/5">
            <img src="{{ asset('images/pet-shelter-svgrepo-com.svg') }}" alt="Menhely logó" class="h-9 w-9 opacity-90" />
          </div>
        </div>

        <div class="min-w-0">
          <h1 class="text-4xl font-semibold tracking-tight text-neutral-900 sm:text-5xl">{{ $shelter->name }}</h1>
          <p class="mt-3 max-w-3xl text-base text-neutral-600 sm:text-lg">{{ \Illuminate\Support\Str::limit($shelter->description, 180) }}</p>

          <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-neutral-600">
            <span class="inline-flex items-center gap-2">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5.006 5.006 0 0 0 5 5Zm0 2c-3.33 0-10 1.667-10 5v1h20v-1c0-3.333-6.67-5-10-5Z"/></svg>
              Tulaj: {{ $shelter->owner?->username ?? $shelter->owner?->name ?? 'Ismeretlen' }}
            </span>
            <span class="inline-flex items-center gap-2">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8Zm0 10a2 2 0 1 1 2-2 2 2 0 0 1-2 2Z"/></svg>
              Létrehozva: {{ $shelter->created_at->format('Y.m.d.') }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>

  @php
    $pets = $shelter->pets()->latest()->get();
  @endphp

  <section class="relative z-10 flex flex-col items-stretch gap-8 p-6 sm:p-10 md:flex-row">
    <div class="flex max-w-xl flex-1 flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-8 shadow-md">
      <div>
        <h2 class="mb-3 text-2xl font-semibold text-neutral-900 sm:text-3xl">Leírás</h2>
        <p class="line-clamp-5 overflow-hidden whitespace-pre-line text-base leading-7 text-neutral-700">
          {{ $shelter->description }}
        </p>
      </div>

      <div class="mt-6 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
        <div class="rounded-xl bg-neutral-50 p-3 ring-1 ring-black/5">
          <div class="text-neutral-500">Utolsó frissítés</div>
          <div class="mt-1 font-medium text-neutral-900">{{ $shelter->updated_at->diffForHumans() }}</div>
        </div>
        <div class="rounded-xl bg-neutral-50 p-3 ring-1 ring-black/5">
          <div class="text-neutral-500">Állatok száma</div>
          <div class="mt-1 font-medium text-neutral-900">{{ $pets->count() }}</div>
        </div>
      </div>
    </div>

    <div class="w-full flex-1">
      <div class="aspect-[16/9] overflow-hidden rounded-2xl shadow-lg">
        <x-ui.slider :images="$shelter->images_safe" :interval="5000" ratio="55%" class="h-full w-full object-cover" />
      </div>
    </div>
  </section>

  <section class="relative z-10 p-6 sm:p-10">
    <h2 class="mb-4 text-2xl font-semibold text-neutral-900 sm:text-3xl">Kisállatok</h2>

    @if($pets->count())
      <div class="space-y-6">
        @foreach($pets as $pet)
          @php
            $imgs = $pet->images_safe ?? (is_array($pet->images) ? $pet->images : json_decode($pet->images ?? '[]', true));
            $img = $imgs[0] ?? 'images/pet-placeholder.png';
          @endphp

          <x-pet.card
            :href="route('pets.show', $pet)"
            :title="$pet->name ?? 'Ismeretlen'"
            :image="$img"
            :badge="match($pet->status ?? null) {
              'adopted' => 'Örökbefogadott',
              'reserved' => 'Foglalva',
              default => 'Elérhető',
            }"
            :description="!empty($pet->description) ? \Illuminate\Support\Str::limit($pet->description, 160) : null"
          />
        @endforeach
      </div>
    @else
      <div class="rounded-2xl border border-neutral-200 bg-white p-8 text-lg text-neutral-700 shadow-sm">
        A menhely még nem töltött fel kisállatot.
      </div>
    @endif
  </section>
</x-app-layout>