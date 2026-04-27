<?php

namespace App\Livewire;

use Livewire\Component;

class ShelterGallery extends Component
{
    public array $images = [];

    public string $name = '';

    public int $currentIndex = 0;

    public bool $isLightboxOpen = false;

    public function mount(array $images, string $name): void
    {
        $this->images = array_values($images);
        $this->name   = $name;
    }

    public function selectImage(int $index): void
    {
        $this->currentIndex = $index;
    }

    public function openLightbox(int $index): void
    {
        $this->currentIndex   = $index;
        $this->isLightboxOpen = true;
    }

    public function closeLightbox(): void
    {
        $this->isLightboxOpen = false;
    }

    public function nextImage(): void
    {
        $count              = count($this->images);
        $this->currentIndex = ($this->currentIndex + 1) % $count;
    }

    public function previousImage(): void
    {
        $count              = count($this->images);
        $this->currentIndex = ($this->currentIndex - 1 + $count) % $count;
    }

    public function render()
    {
        return view('livewire.shelter-gallery');
    }
}
