import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('speciesBreedSelect', (species, breeds, speciesId, breedId) => ({
    speciesOpen: false,
    breedOpen: false,
    species,
    breeds,
    speciesId,
    breedId,

    get selectedSpeciesLabel() {
        return this.species.find(i => i.id === this.speciesId)?.name ?? 'Válassz fajt'
    },
    get filteredBreeds() {
        return this.breeds.filter(i => i.species_id === this.speciesId)
    },
    get selectedBreedLabel() {
        return this.filteredBreeds.find(i => i.id === this.breedId)?.name ?? 'Válassz fajtát'
    },

    selectSpecies(id) {
        this.speciesId = id
        this.breedId = this.filteredBreeds[0]?.id ?? ''
        this.speciesOpen = false
        this.breedOpen = false
    },
    selectBreed(id) {
        this.breedId = id
        this.breedOpen = false
    },
}))

Alpine.start();
