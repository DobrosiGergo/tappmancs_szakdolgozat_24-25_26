<?php

namespace App\Livewire;

use App\Models\Breed;
use App\Models\Specie;
use Livewire\Component;

class PetSpeciesBreedSelect extends Component
{
    public $species = [];

    public $breeds = [];

    public $species_id = null;

    public $breed_id = null;

    public function mount($speciesId = null, $breedId = null)
    {
        $this->species = Specie::orderBy('name')->get(['id', 'name'])->toArray();

        $this->species_id = $speciesId ?? (old('species_id') ?: ($this->species[0]['id'] ?? null));
        $this->breed_id   = $breedId   ?? old('breed_id');

        $this->loadBreeds();

        if ($this->breed_id && ! collect($this->breeds)->pluck('id')->contains((int) $this->breed_id)) {
            $this->breed_id = null;
        }

        if (! $this->breed_id) {
            $this->setDefaultBreed();
        }
    }

    public function updatedSpeciesId()
    {
        $this->breed_id = null;
        $this->loadBreeds();
        $this->setDefaultBreed();
    }

    private function loadBreeds(): void
    {
        $this->breeds = $this->species_id
            ? Breed::where('species_id', $this->species_id)->orderBy('name')->get(['id', 'name'])->toArray()
            : [];
    }

    private function setDefaultBreed(): void
    {
        $mix = collect($this->breeds)->firstWhere('name', 'Keverék')
            ?? collect($this->breeds)->firstWhere('name', 'Ismeretlen')
            ?? ($this->breeds[0] ?? null);

        if ($mix) {
            $this->breed_id = $mix['id'];
        }
    }

    public function render()
    {
        return view('livewire.pet-species-breed-select');
    }
}
