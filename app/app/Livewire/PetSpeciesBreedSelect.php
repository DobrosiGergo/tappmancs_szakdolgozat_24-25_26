<?php

namespace App\Livewire;

use App\Models\Breed;
use App\Models\Specie;
use Livewire\Component;

class PetSpeciesBreedSelect extends Component
{
    public array $species = [];

    public array $breeds = [];

    public string $species_id = '';

    public string $breed_id = '';

    public function mount($speciesId = null, $breedId = null): void
    {
        $this->species = Specie::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($specie) => [
                'id'   => (string) $specie->id,
                'name' => $specie->name,
            ])
            ->toArray();

        $this->breeds = Breed::query()
            ->orderBy('name')
            ->get(['id', 'name', 'species_id'])
            ->map(fn ($breed) => [
                'id'         => (string) $breed->id,
                'name'       => $breed->name,
                'species_id' => (string) $breed->species_id,
            ])
            ->toArray();

        if ($speciesId) {
            $defaultSpeciesId = $speciesId;
        } else {
            $defaultSpeciesId = $this->species[0]['id'] ?? '';
        }

        $this->species_id = (string) old('species_id', $defaultSpeciesId);

        $availableBreeds = collect($this->breeds)
            ->where('species_id', $this->species_id)
            ->values();

        $incomingBreedId = (string) old('breed_id', $breedId);

        if ($availableBreeds->contains('id', $incomingBreedId)) {
            $this->breed_id = $incomingBreedId;
        } else {
            $this->breed_id = (string) ($availableBreeds->first()['id'] ?? '');
        }
    }

    public function render()
    {
        return view('livewire.pet-species-breed-select');
    }
}
