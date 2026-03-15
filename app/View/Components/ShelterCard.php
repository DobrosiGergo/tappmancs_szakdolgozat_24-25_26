<?php

namespace App\View\Components;

use App\Models\Shelter;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShelterCard extends Component
{
    public Shelter $shelter;

    public function __construct(Shelter $shelter)
    {
        $this->shelter = $shelter;
    }

    public function imageUrl(): string
    {
        return ! empty($this->shelter->images[0]) ? asset('storage/' . $this->shelter->images[0]) : 'https://placehold.co/300x200?text=Nincs+kép&font=roboto';
    }

    public function render(): View|Closure|string
    {
        return view('components.shelter-card');
    }
}
