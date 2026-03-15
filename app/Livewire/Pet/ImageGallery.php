<?php

namespace App\Livewire\Pet;

use Livewire\Component;

class ImageGallery extends Component
{
    public array $images = [];

    public ?string $selectedImage = null;

    public function mount(array $images = []): void
    {
        $this->images        = array_values($images);
        $this->selectedImage = $this->images[0] ?? null;
    }

    public function selectImage(string $image): void
    {
        if (in_array($image, $this->images, true)) {
            $this->selectedImage = $image;
        }
    }

    public function render()
    {
        return view('livewire.pet.image-gallery');
    }
}
