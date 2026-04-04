<?php

namespace App\Livewire;

use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUploader extends Component
{
    use WithFileUploads;

    public string $context = 'shelter';

    public int $max = 10;

    public int $maxSize = 2048;

    public array $images = [];

    public array $previews = [];

    public array $existingImages = [];

    public ?int $petId = null;

    public string $uid;

    public function mount(
        string $context = 'shelter',
        int $max = 10,
        int $maxSize = 2048,
        ?int $petId = null
    ): void {
        $this->context = $context;
        $this->max     = $max;
        $this->maxSize = $maxSize;
        $this->petId   = $petId;
        $this->uid     = uniqid($context . '_');

        $existing       = session()->get($this->sessionKey(), []);
        $this->previews = collect($existing)->map(fn ($path) => [
            'name' => basename($path),
            'path' => $path,
        ])->toArray();

        if ($this->context === 'pet' && $this->petId) {
            $pet = Pet::query()->find($this->petId);

            $this->existingImages = collect((array) $pet?->images)->map(fn ($path) => [
                'name' => basename($path),
                'path' => $path,
            ])->values()->toArray();
        }
    }

    protected function sessionKey(): string
    {
        return $this->context . '_temp_images';
    }

    protected function tempDir(): string
    {
        return 'temp/' . $this->context;
    }

    public function updatedImages(): void
    {
        $this->validate([
            'images'   => 'array|max:' . $this->max,
            'images.*' => 'image|max:' . $this->maxSize,
        ]);

        foreach ($this->images as $image) {
            $path = $image->store($this->tempDir(), 'public');

            $this->previews[] = [
                'name' => $image->getClientOriginalName(),
                'path' => $path,
            ];
        }

        session()->put(
            $this->sessionKey(),
            collect($this->previews)->pluck('path')->toArray()
        );

        $this->images = [];
    }

    public function removeImage(int $index): void
    {
        if (! isset($this->previews[$index])) {
            return;
        }

        $path = $this->previews[$index]['path'];

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        unset($this->previews[$index]);
        $this->previews = array_values($this->previews);

        session()->put(
            $this->sessionKey(),
            collect($this->previews)->pluck('path')->toArray()
        );
    }

    public function removeExistingImage(int $index): void
    {
        if ($this->context !== 'pet' || ! $this->petId) {
            return;
        }

        if (! isset($this->existingImages[$index])) {
            return;
        }

        $pet = Pet::query()->find($this->petId);

        if (! $pet) {
            return;
        }

        $images = collect((array) $pet->images)->values();

        if (! isset($images[$index])) {
            return;
        }

        $path = $images[$index];

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $images->forget($index);

        $pet->update([
            'images' => $images->values()->toArray(),
        ]);

        $this->existingImages = $images->values()->map(fn ($path) => [
            'name' => basename($path),
            'path' => $path,
        ])->toArray();
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
