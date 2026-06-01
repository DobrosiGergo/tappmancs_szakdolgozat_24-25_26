<?php

namespace App\Livewire;

use App\Models\Pet;
use App\Models\Specie;
use Livewire\Component;

class PetFilter extends Component
{
    public string $species = '';
    public string $gender  = '';
    public string $status  = '';

    public function mount(): void
    {
        $this->species = request('species', '');
        $this->gender  = request('gender', '');
        $this->status  = request('status', '');
    }

    public function toggle(string $filter, string $value): void
    {
        if ($this->$filter === $value) {
            $this->$filter = '';
        } else {
            $this->$filter = $value;
        }

        $this->redirect(route('pets.index', array_filter([
            'search'  => request('search'),
            'species' => $this->species,
            'gender'  => $this->gender,
            'status'  => $this->status,
        ])));
    }

    public function render()
    {
        return view('livewire.pet-filter', [
            'filterGroups' => [
                ['name' => 'species', 'label' => 'Faj',    'options' => Specie::selectOptions()],
                ['name' => 'gender',  'label' => 'Nem',     'options' => Pet::genderOptions()],
                ['name' => 'status',  'label' => 'Státusz', 'options' => Pet::statusOptions()],
            ],
        ]);
    }
}
