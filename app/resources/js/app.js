import './bootstrap';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('speciesBreedSelect', (species, breeds, speciesId, breedId) => ({
        speciesOpen: false,
        breedOpen: false,
        species,
        breeds,
        speciesId,
        breedId,

        get selectedSpeciesLabel() {
            const match = this.species.find(s => s.id === this.speciesId);
            return match?.name ?? 'Válassz fajt';
        },

        get filteredBreeds() {
            return this.breeds.filter(b => b.species_id === this.speciesId);
        },

        get selectedBreedLabel() {
            const match = this.filteredBreeds.find(b => b.id === this.breedId);
            return match?.name ?? 'Válassz fajtát';
        },

        selectSpecies(id) {
            this.speciesId = id;
            this.breedId = this.filteredBreeds[0]?.id ?? '';
            this.speciesOpen = false;
            this.breedOpen = false;
        },

        selectBreed(id) {
            this.breedId = id;
            this.breedOpen = false;
        },
    }));
});
