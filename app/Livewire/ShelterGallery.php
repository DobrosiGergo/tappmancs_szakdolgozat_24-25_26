<?php

namespace App\Livewire;

use Livewire\Component;

class ShelterGallery extends Component
{
    public array $images = [];

    public string $name = 'Menhely';

    public ?string $selectedImage = null;

    public bool $isLightboxOpen = false;

    public function mount(array $images = [], string $name = 'Menhely'): void
    {
        $this->images = array_values($images);
        $this->name = $name;
        $this->selectedImage = $this->images[0] ?? null;
    }

    public function selectImage(string $image): void
    {
        if (! in_array($image, $this->images, true)) {
            return;
        }

        $this->selectedImage = $image;
    }

    public function openLightbox(?string $image = null): void
    {
        if ($image !== null && in_array($image, $this->images, true)) {
            $this->selectedImage = $image;
        }

        if (! $this->selectedImage) {
            return;
        }

        $this->isLightboxOpen = true;
    }

    public function closeLightbox(): void
    {
        $this->isLightboxOpen = false;
    }

    public function previousImage(): void
    {
        if (count($this->images) <= 1 || ! $this->selectedImage) {
            return;
        }

        $currentIndex = array_search($this->selectedImage, $this->images, true);

        if ($currentIndex === false) {
            $this->selectedImage = $this->images[0] ?? null;
            return;
        }

        $previousIndex = $currentIndex === 0
            ? count($this->images) - 1
            : $currentIndex - 1;

        $this->selectedImage = $this->images[$previousIndex];
    }

    public function nextImage(): void
    {
        if (count($this->images) <= 1 || ! $this->selectedImage) {
            return;
        }

        $currentIndex = array_search($this->selectedImage, $this->images, true);

        if ($currentIndex === false) {
            $this->selectedImage = $this->images[0] ?? null;
            return;
        }

        $nextIndex = $currentIndex === count($this->images) - 1
            ? 0
            : $currentIndex + 1;

        $this->selectedImage = $this->images[$nextIndex];
    }

    public function render()
    {
        return view('livewire.shelter-gallery');
    }
}