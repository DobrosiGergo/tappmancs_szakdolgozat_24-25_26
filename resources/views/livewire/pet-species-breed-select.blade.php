<div class="space-y-5 md:col-span-2">
  <div>
    <label for="species_id" class="mb-1 block text-sm font-medium text-neutral-700">
      Válassz fajt*
    </label>

    <select
      id="species_id"
      wire:model.live="species_id"
      class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-neutral-900 shadow-sm transition focus:border-neutral-800 focus:ring-neutral-800"
    >
      @foreach($species as $sp)
        <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
      @endforeach
    </select>

    <input type="hidden" name="species_id" value="{{ $species_id }}">

    @error('species_id')
      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <label for="breed_id" class="mb-1 block text-sm font-medium text-neutral-700">
      Válassz fajtát*
    </label>

    <select
      id="breed_id"
      wire:model.live="breed_id"
      class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-neutral-900 shadow-sm transition focus:border-neutral-800 focus:ring-neutral-800"
    >
      @foreach($breeds as $br)
        <option value="{{ $br['id'] }}">{{ $br['name'] }}</option>
      @endforeach
    </select>

    <input type="hidden" name="breed_id" value="{{ $breed_id }}">

    @error('breed_id')
      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
  </div>
</div>