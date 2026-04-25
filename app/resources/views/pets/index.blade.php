<x-app-layout>
  <x-ui.breadcrumbs
    :items="[
      ['label' => 'Kisállatok']
    ]"
  />

  <h1 class="text-2xl font-bold mb-6">Elérhető kisállatok</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($pets as $pet)
      @php
        $desc = \Illuminate\Support\Str::limit($pet->description ?? '', 120);

        $imgs = is_array($pet->images) ? $pet->images : json_decode($pet->images ?? '[]', true);
        $image = $imgs[0] ?? null;

        $badge = $pet->status === 'adopted' ? 'Örökbefogadott' : 'Elérhető';

        $meta = [
          'Faj' => $pet->species?->name,
          'Fajta' => $pet->breed?->name,
          'Kor' => isset($pet->age) ? ($pet->age.' év') : null,
        ];
      @endphp

      <x-pet.card
        :href="route('pets.show', $pet)"
        :title="$pet->name"
        :description="$desc"
        :image="$image"
        :badge="$badge"
        :shelterName="$pet->shelter?->name"
        :meta="$meta"
      />
    @empty
      <div class="col-span-full text-neutral-500">Nincs megjeleníthető kisállat.</div>
    @endforelse
  </div>

  @if(method_exists($pets, 'links'))
    <div class="mt-6">
      {{ $pets->withQueryString()->onEachSide(1)->links() }}
    </div>
  @endif
</x-app-layout>