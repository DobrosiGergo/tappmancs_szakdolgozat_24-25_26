<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
  <div>
    <label class="block text-sm font-medium mb-1">Faj</label>
    <select
      wire:model.live="species_id"
      class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800"
    >
      @foreach($species as $sp)
        <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
      @endforeach
    </select>

    <input type="hidden" name="species_id" value="{{ $species_id }}">
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Fajta</label>
    <select
      wire:model.live="breed_id"
      class="w-full rounded-lg border-neutral-300 focus:border-neutral-800 focus:ring-neutral-800"
      @disabled(empty($breeds))
    >
      @foreach($breeds as $br)
        <option value="{{ $br['id'] }}">{{ $br['name'] }}</option>
      @endforeach
    </select>

    <input type="hidden" name="breed_id" value="{{ $breed_id }}">
  </div>
</div>