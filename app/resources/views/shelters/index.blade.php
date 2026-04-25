<x-app-layout>
<x-ui.breadcrumbs
  :items="[
    ['label' => 'Menhelyek']
  ]"
/>
  <h1 class="text-2xl font-bold mb-6">Elérhető menhelyek</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($shelters as $shelter)
      @php
        $desc  = \Illuminate\Support\Str::limit($shelter->description ?? '', 120);
        $badge = isset($shelter->pets_count) ? $shelter->pets_count.' kisállat' : null;
      @endphp

      <x-shelter.card
        :href="route('shelters.show', $shelter)"
        :title="$shelter->name"
        :description="$desc"
        :badge="$badge"
      />
    @empty
      <div class="col-span-full text-neutral-500">Nincs megjeleníthető menhely.</div>
    @endforelse
  </div>

  @if(method_exists($shelters, 'links'))
  <div class="mt-6">
  {{ $shelters->withQueryString()->onEachSide(1)->links() }}
  </div>
  @endif
</x-app-layout>
