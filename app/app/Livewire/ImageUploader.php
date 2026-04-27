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
        $totalAfter = count($this->previews) + count($this->existingImages) + count($this->images);

        if ($totalAfter > $this->max) {
            $this->addError('images', "Legfeljebb {$this->max} kép tölthető fel összesen.");
            $this->images = [];

            return;
        }

        $this->validate([
            'images'   => 'array|max:' . $this->max,
            'images.*' => 'image|mimes:jpeg,jpg,png|max:' . $this->maxSize,
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

        abort_unless(
            $pet->employee_id           === auth()->id()
            || $pet->shelter?->owner_id === auth()->id(),
            403
        );

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
        $used      = count($this->existingImages) + count($this->previews);
        $isAtMax   = $used               >= $this->max;
        $isNearMax = ! $isAtMax && $used >= (int) ceil($this->max * 0.8);

        return view('livewire.image-uploader', [
            'used'          => $used,
            'isAtMax'       => $isAtMax,
            'isNearMax'     => $isNearMax,
            'fileSizeLabel' => $this->maxSize >= 1024
                ? round($this->maxSize / 1024) . ' MB'
                : $this->maxSize . ' KB',
            'badgeClass' => match (true) {
                $isAtMax   => 'bg-red-50 text-red-600 ring-red-200',
                $isNearMax => 'bg-amber-50 text-amber-600 ring-amber-200',
                default    => 'bg-neutral-100 text-neutral-600 ring-neutral-200',
            },
            'barClass' => match (true) {
                $isAtMax   => 'bg-red-500',
                $isNearMax => 'bg-amber-400',
                default    => 'bg-neutral-900',
            },
        ]);
    }
}
