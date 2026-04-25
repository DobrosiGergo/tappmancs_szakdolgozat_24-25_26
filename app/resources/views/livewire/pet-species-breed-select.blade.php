<div
  x-data="{
    speciesOpen: false,
    breedOpen: false,

    species: @js($species),
    breeds: @js($breeds),

    speciesId: @js($species_id),
    breedId: @js($breed_id),

    get selectedSpeciesLabel() {
      return this.species.find(item => item.id === this.speciesId)?.name ?? 'Válassz fajt'
    },

    get filteredBreeds() {
      return this.breeds.filter(item => item.species_id === this.speciesId)
    },

    get selectedBreedLabel() {
      return this.filteredBreeds.find(item => item.id === this.breedId)?.name ?? 'Válassz fajtát'
    },

    selectSpecies(id) {
      this.speciesId = id

      const firstBreed = this.filteredBreeds[0]
      this.breedId = firstBreed ? firstBreed.id : ''

      this.speciesOpen = false
      this.breedOpen = false
    },

    selectBreed(id) {
      this.breedId = id
      this.breedOpen = false
    },
  }"
  class="grid grid-cols-1 sm:grid-cols-2 gap-5"
>
  <div class="flex flex-col gap-1.5">
    <label class="text-sm font-medium text-neutral-700">
      Válassz fajt*
    </label>

    <div class="relative" @click.outside="speciesOpen = false">
      <button
        type="button"
        @click="speciesOpen = !speciesOpen"
        :class="speciesOpen ? 'border-neutral-900 ring-2 ring-neutral-900/10' : 'border-neutral-300 hover:border-neutral-500'"
        class="w-full flex items-center justify-between rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition focus:outline-none"
      >
        <span x-text="selectedSpeciesLabel" class="truncate text-left"></span>

        <img
          src="{{ asset('images/next.svg') }}"
          alt=""
          class="ml-2 h-4 w-4 shrink-0 rotate-90 transition-transform duration-200"
          :class="{ 'rotate-180': speciesOpen }"
        >
      </button>

      <input type="hidden" name="species_id" :value="speciesId">

      <div
        x-show="speciesOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-20 mt-1 w-full overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg"
      >
        <div class="max-h-56 overflow-y-auto py-1">
          <template x-for="item in species" :key="item.id">
            <button
              type="button"
              @click="selectSpecies(item.id)"
              :class="speciesId === item.id
                ? 'bg-neutral-900 text-white'
                : 'text-neutral-700 hover:bg-neutral-200 hover:text-neutral-950'"
              class="flex w-full items-center justify-between px-4 py-2.5 text-sm transition"
            >
              <span x-text="item.name"></span>

              <img
                x-show="speciesId === item.id"
                src="{{ asset('images/check.svg') }}"
                alt=""
                class="h-4 w-4 shrink-0 brightness-0 invert"
              >
            </button>
          </template>
        </div>
      </div>
    </div>

    @error('species_id')
      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
  </div>

  <div class="flex flex-col gap-1.5">
    <label class="text-sm font-medium text-neutral-700">
      Válassz fajtát*
    </label>

    <div class="relative" @click.outside="breedOpen = false">
      <button
        type="button"
        @click="if (filteredBreeds.length) breedOpen = !breedOpen"
        :class="breedOpen ? 'border-neutral-900 ring-2 ring-neutral-900/10' : 'border-neutral-300 hover:border-neutral-500'"
        class="w-full flex items-center justify-between rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition focus:outline-none"
        :disabled="!filteredBreeds.length"
      >
        <span x-text="selectedBreedLabel" class="truncate text-left"></span>

        <img
          src="{{ asset('images/next.svg') }}"
          alt=""
          class="ml-2 h-4 w-4 shrink-0 rotate-90 transition-transform duration-200"
          :class="{ 'rotate-180': breedOpen }"
        >
      </button>

      <input type="hidden" name="breed_id" :value="breedId">

      <div
        x-show="breedOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-20 mt-1 w-full overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg"
      >
        <div class="max-h-56 overflow-y-auto py-1">
          <template x-for="item in filteredBreeds" :key="item.id">
            <button
              type="button"
              @click="selectBreed(item.id)"
              :class="breedId === item.id
                ? 'bg-neutral-900 text-white'
                : 'text-neutral-700 hover:bg-neutral-200 hover:text-neutral-950'"
              class="flex w-full items-center justify-between px-4 py-2.5 text-sm transition"
            >
              <span x-text="item.name"></span>

              <img
                x-show="breedId === item.id"
                src="{{ asset('images/check.svg') }}"
                alt=""
                class="h-4 w-4 shrink-0 brightness-0 invert"
              >
            </button>
          </template>

          <div
            x-show="!filteredBreeds.length"
            class="px-4 py-3 text-sm text-neutral-500"
          >
            Ehhez a fajhoz még nincs fajta rögzítve.
          </div>
        </div>
      </div>
    </div>

    @error('breed_id')
      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
  </div>
</div>