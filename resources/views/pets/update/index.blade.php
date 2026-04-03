<x-app-layout>
  <x-ui.breadcrumbs
    :items="[
      ['label' => 'Kisállatok'],
      ['label' => 'Saját kisállataim']
    ]"
  />

  <div class="mb-6 flex items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold">Saját feltöltött kisállatok</h1>
      <p class="mt-1 text-sm text-neutral-500">
        Itt tudod megtekinteni és módosítani a saját feltöltött kisállataidat.
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    @forelse($pets as $pet)
      @php
        $desc = \Illuminate\Support\Str::limit($pet->description ?? '', 120);

        $imgs = is_array($pet->images)
          ? $pet->images
          : json_decode($pet->images ?? '[]', true);

        $image = $imgs[0] ?? null;

        $badge = $pet->status === 'adopted'
          ? 'Örökbefogadott'
          : 'Elérhető';

        $meta = [
          'Faj' => $pet->species?->name,
          'Fajta' => $pet->breed?->name,
          'Kor' => isset($pet->age) ? ($pet->age . ' év') : null,
        ];
      @endphp

      <div class="relative">
        <x-pet.card
          :href="route('pets.show', $pet)"
          :title="$pet->name"
          :description="$desc"
          :image="$image"
          :badge="$badge"
          :shelterName="$pet->shelter?->name"
          :meta="$meta"
        />

        <div class="absolute right-4 top-4 z-10 flex items-center gap-2">
          <a
            href="{{ route('pets.edit', $pet) }}"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-neutral-200 bg-white/90 text-neutral-700 shadow-sm backdrop-blur transition hover:bg-neutral-300 hover:text-white"
            title="Módosítás"
          >
            <img
              src="{{ asset('images/pencil.svg') }}"
              alt="Módosítás"
              class="h-5 w-5"
            >
          </a>

          <button
            type="button"
            x-data
            @click.prevent="$dispatch('open-modal', 'delete-pet-{{ $pet->id }}')"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-red-200 bg-white/90 text-red-600 shadow-sm backdrop-blur transition hover:bg-red-500 hover:text-white"
            title="Törlés"
          >
            <img
              src="{{ asset('images/delete.svg') }}"
              alt="Törlés"
              class="h-5 w-5"
            >
          </button>
        </div>

        <x-modal name="delete-pet-{{ $pet->id }}" :show="false" focusable>
          <div class="p-6">
            <h2 class="text-lg font-semibold text-neutral-900">
              Kisállat törlése
            </h2>

            <p class="mt-2 text-sm text-neutral-600">
              Biztosan törölni szeretnéd ezt a kisállatot?
              Ez a művelet nem visszavonható, és a hozzá tartozó képek is törlésre kerülnek.
            </p>

            <div class="mt-6 flex items-center justify-end gap-3">
              <button
                type="button"
                x-on:click="$dispatch('close')"
                class="inline-flex items-center rounded-xl border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100"
              >
                Mégse
              </button>

              <form method="POST" action="{{ route('pets.destroy', $pet) }}">
                @csrf
                @method('DELETE')

                <button
                  type="submit"
                  class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                >
                  Törlés
                </button>
              </form>
            </div>
          </div>
        </x-modal>
      </div>
    @empty
      <div class="col-span-full rounded-2xl border border-dashed border-neutral-300 bg-white/70 p-8 text-center text-neutral-500">
        Jelenleg nincs saját feltöltött kisállatod.
      </div>
    @endforelse
  </div>

  @if(method_exists($pets, 'links'))
    <div class="mt-6">
      {{ $pets->withQueryString()->onEachSide(1)->links() }}
    </div>
  @endif
</x-app-layout>