<?php

namespace App\Livewire;

use App\Models\Breed;
use App\Models\Specie;
use Livewire\Component;

class PetSpeciesBreedSelect extends Component
{
    public array $species = [];

    public array $breeds = [];

    public $species_id = '';

    public $breed_id = '';

    public function mount($speciesId = null, $breedId = null): void
    {
        $this->species = Specie::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        $incomingSpeciesId = old('species_id', $speciesId);
        $incomingBreedId   = old('breed_id', $breedId);

        if (! empty($incomingSpeciesId)) {
            $this->species_id = (string) $incomingSpeciesId;
        } elseif (! empty($this->species)) {
            $this->species_id = (string) $this->species[0]['id'];
        }

        $this->loadBreeds();

        if (! empty($incomingBreedId)) {
            $breedExistsForSpecies = collect($this->breeds)
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->contains((string) $incomingBreedId);

            $this->breed_id = $breedExistsForSpecies
                ? (string) $incomingBreedId
                : '';
        }

        if ($this->breed_id === '' && ! empty($this->breeds)) {
            $this->breed_id = (string) $this->breeds[0]['id'];
        }
    }

    public function updatedSpeciesId($value): void
    {
        $this->species_id = (string) ($value ?? '');
        $this->loadBreeds();

        $this->breed_id = ! empty($this->breeds)
            ? (string) $this->breeds[0]['id']
            : '';
    }

    protected function loadBreeds(): void
    {
        if ($this->species_id === '') {
            $this->breeds = [];

            return;
        }

        $this->breeds = Breed::query()
            ->where('species_id', $this->species_id)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pet-species-breed-select');
    }
}
