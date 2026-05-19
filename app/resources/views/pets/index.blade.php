<x-app-layout>
  <x-ui.breadcrumbs :items="[['label' => 'Kisállatok']]" />

  <h1 class="text-2xl font-bold mb-6">Elérhető kisállatok</h1>

  <x-ui.search-filter
    :action="route('pets.index')"
    placeholder="Keress név, fajta vagy város szerint..."
    class="mb-3"
  />

  <div class="py-3">
    <p class="text-sm text-neutral-500">
      <span class="font-bold text-neutral-900 text-base">{{ $pets->total() }}</span> találat
    </p>
  </div>

  <hr class="border-neutral-200 mb-6" />

  <div class="flex flex-col lg:flex-row gap-8">

    <aside class="w-full lg:w-56 shrink-0">
      <p class="text-xs font-semibold text-neutral-500 uppercase tracking-widest mb-4">Szűrők</p>
      <livewire:pet-filter />
    </aside>

    <div class="flex-1 min-w-0">
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($pets as $pet)
          <x-pet.card
            :href="route('pets.show', $pet)"
            :title="$pet->name"
            :description="$pet->excerpt"
            :image="$pet->first_image_path"
            :badge="$pet->status_label"
            :shelterName="$pet->shelter?->name"
            :meta="['Faj' => $pet->species?->name, 'Fajta' => $pet->breed?->name, 'Kor' => $pet->age_label]"
          />
        @empty
          <div class="col-span-full text-neutral-500">Nincs megjeleníthető kisállat.</div>
        @endforelse
      </div>

      <div class="mt-6">
        {{ $pets->withQueryString()->onEachSide(1)->links() }}
      </div>
    </div>

  </div>
</x-app-layout>
