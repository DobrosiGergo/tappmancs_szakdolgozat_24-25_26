<x-app-layout>
  <x-ui.breadcrumbs :items="[['label' => 'Menhelyek']]" />

  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <h1 class="text-2xl font-bold text-neutral-900">Elérhető menhelyek</h1>
    <div class="w-full sm:w-72">
      <x-ui.search-filter
        :action="route('shelters.index')"
        placeholder="Menhely neve..."
      />
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($shelters as $shelter)
      <x-shelter.card
        :href="route('shelters.show', $shelter)"
        :title="$shelter->name"
        :description="$shelter->excerpt"
        :badge="$shelter->pets_count . ' kisállat'"
        :image="$shelter->cover_image"
      />
    @empty
      <div class="col-span-full text-neutral-500">Nincs megjeleníthető menhely.</div>
    @endforelse
  </div>

  <div class="mt-6">
    {{ $shelters->withQueryString()->onEachSide(1)->links() }}
  </div>
</x-app-layout>
