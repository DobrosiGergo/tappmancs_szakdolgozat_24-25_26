<div
    x-data="speciesBreedSelect(@js($species), @js($breeds), @js($species_id), @js($breed_id))"
    class="grid grid-cols-1 sm:grid-cols-2 gap-5"
>
    <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium text-neutral-700">Válassz fajt*</label>

        <div class="relative" @click.outside="speciesOpen = false">
            <button
                type="button"
                @click="speciesOpen = !speciesOpen"
                :class="speciesOpen ? 'border-neutral-900 ring-2 ring-neutral-900/10' : 'border-neutral-300 hover:border-neutral-500'"
                class="w-full flex items-center justify-between rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition focus:outline-none"
            >
                <span x-text="selectedSpeciesLabel" class="truncate text-left"></span>
                <img src="{{ asset('images/next.svg') }}" alt="" class="ml-2 h-4 w-4 shrink-0 rotate-90 transition-transform duration-200" :class="{ 'rotate-180': speciesOpen }">
            </button>

            <input type="hidden" name="species_id" :value="speciesId">

            <x-ui.dropdown-panel x-show="speciesOpen">
                <div class="max-h-56 overflow-y-auto py-1">
                    <template x-for="item in species" :key="item.id">
                        <button
                            type="button"
                            @click="selectSpecies(item.id)"
                            :class="speciesId === item.id ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-200 hover:text-neutral-950'"
                            class="flex w-full items-center justify-between px-4 py-2.5 text-sm transition"
                        >
                            <span x-text="item.name"></span>
                            <img x-show="speciesId === item.id" src="{{ asset('images/check.svg') }}" alt="" class="h-4 w-4 shrink-0 brightness-0 invert">
                        </button>
                    </template>
                </div>
            </x-ui.dropdown-panel>
        </div>

        @error('species_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium text-neutral-700">Válassz fajtát*</label>

        <div class="relative" @click.outside="breedOpen = false">
            <button
                type="button"
                @click="if (filteredBreeds.length) breedOpen = !breedOpen"
                :class="breedOpen ? 'border-neutral-900 ring-2 ring-neutral-900/10' : 'border-neutral-300 hover:border-neutral-500'"
                class="w-full flex items-center justify-between rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition focus:outline-none"
                :disabled="!filteredBreeds.length"
            >
                <span x-text="selectedBreedLabel" class="truncate text-left"></span>
                <img src="{{ asset('images/next.svg') }}" alt="" class="ml-2 h-4 w-4 shrink-0 rotate-90 transition-transform duration-200" :class="{ 'rotate-180': breedOpen }">
            </button>

            <input type="hidden" name="breed_id" :value="breedId">

            <x-ui.dropdown-panel x-show="breedOpen">
                <div class="max-h-56 overflow-y-auto py-1">
                    <template x-for="item in filteredBreeds" :key="item.id">
                        <button
                            type="button"
                            @click="selectBreed(item.id)"
                            :class="breedId === item.id ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-200 hover:text-neutral-950'"
                            class="flex w-full items-center justify-between px-4 py-2.5 text-sm transition"
                        >
                            <span x-text="item.name"></span>
                            <img x-show="breedId === item.id" src="{{ asset('images/check.svg') }}" alt="" class="h-4 w-4 shrink-0 brightness-0 invert">
                        </button>
                    </template>

                    <div x-show="!filteredBreeds.length" class="px-4 py-3 text-sm text-neutral-500">
                        Ehhez a fajhoz még nincs fajta rögzítve.
                    </div>
                </div>
            </x-ui.dropdown-panel>
        </div>

        @error('breed_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
