<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Models\Pet;
use App\Models\Shelter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PetController extends Controller
{
    public function create()
    {
        $shelter = Shelter::where('owner_id', auth()->id())->firstOrFail();

        return view('pets.create', compact('shelter'));
    }

    public function index()
    {
        $pets = Pet::query()
            ->with(['shelter:id,name', 'species:id,name', 'breed:id,name'])
            ->latest()
            ->paginate(12);

        return view('pets.index', compact('pets'));
    }

    public function updateIndex()
    {
        $userId = auth()->id();

        $pets = Pet::query()
            ->with([
                'shelter:id,name,owner_id',
                'species:id,name',
                'breed:id,name',
            ])
            ->where(function ($query) use ($userId) {
                $query->where('employee_id', $userId)
                    ->orWhereHas('shelter', function ($shelterQuery) use ($userId) {
                        $shelterQuery->where('owner_id', $userId);
                    });
            })
            ->latest()
            ->paginate(12);

        return view('pets.update.index', compact('pets'));
    }

    public function show(Pet $pet)
    {
        $pet->load([
            'shelter:id,name,uuid,description',
            'species:id,name',
            'breed:id,name',
            'employee:id,name',
        ]);

        $relatedPets = Pet::query()
            ->where('shelter_id', $pet->shelter_id)
            ->whereKeyNot($pet->id)
            ->with([
                'species:id,name',
                'breed:id,name',
                'shelter:id,name,uuid',
            ])
            ->latest()
            ->paginate(6, ['*'], 'related_page');

        return view('pets.show', compact('pet', 'relatedPets'));
    }

    public function edit(Pet $pet)
    {
        $userId = auth()->id();

        $pet->load([
            'shelter:id,name,owner_id',
            'species:id,name',
            'breed:id,name',
        ]);

        $isOwner    = $pet->shelter && $pet->shelter->owner_id === $userId;
        $isEmployee = $pet->employee_id                        === $userId;

        abort_unless($isOwner || $isEmployee, 403);

        $shelter = Shelter::where('owner_id', auth()->id())->first();

        if (! $shelter && $pet->shelter) {
            $shelter = $pet->shelter;
        }

        return view('pets.update.edit', compact('pet', 'shelter'));
    }

    public function store(PetStoreRequest $request)
    {
        $data    = $request->validated();
        $shelter = Shelter::where('owner_id', auth()->id())->firstOrFail();

        $slug = Str::slug($data['name']);

        $pet = Pet::create([
            'name'         => $data['name'],
            'slug'         => $slug,
            'species_id'   => $data['species_id'],
            'age'          => $data['age'],
            'arrival_date' => $data['arrival_date'] ?? now(),
            'employee_id'  => auth()->id(),
            'shelter_id'   => $shelter->id,
            'status'       => $data['status'] ?? 'free',
            'description'  => $data['description'],
            'images'       => [],
            'breed_id'     => $data['breed_id'],
        ]);

        $paths = [];

        if (session()->has('pet_temp_images')) {
            foreach (session('pet_temp_images') as $tmpPath) {
                $filename = basename($tmpPath);
                $newPath  = 'pets/' . $shelter->uuid . '/' . $pet->uuid . '/' . $filename;

                if (Storage::disk('public')->exists($tmpPath)) {
                    Storage::disk('public')->move($tmpPath, $newPath);
                    $paths[] = $newPath;
                }
            }

            session()->forget('pet_temp_images');
        }

        $pet->update([
            'images' => $paths,
        ]);

        return redirect()
            ->route('pets.show', $pet)
            ->with('flash', [
                'message' => 'Kisállat létrehozva.',
                'type'    => 'success',
            ]);
    }

    public function update(PetStoreRequest $request, Pet $pet)
    {
        $userId = auth()->id();

        $pet->load([
            'shelter:id,name,uuid,owner_id',
        ]);

        $isOwner    = $pet->shelter && $pet->shelter->owner_id === $userId;
        $isEmployee = $pet->employee_id                        === $userId;

        abort_unless($isOwner || $isEmployee, 403);

        $data = $request->validated();

        $slug = Str::slug($data['name']);

        $currentImages = is_array($pet->images)
            ? $pet->images
            : json_decode($pet->images ?? '[]', true);

        $currentImages = is_array($currentImages) ? $currentImages : [];

        $newImages = [];

        if (session()->has('pet_temp_images')) {
            foreach (session('pet_temp_images') as $tmpPath) {
                $filename = basename($tmpPath);
                $newPath  = 'pets/' . $pet->shelter->uuid . '/' . $pet->uuid . '/' . $filename;

                if (Storage::disk('public')->exists($tmpPath)) {
                    Storage::disk('public')->move($tmpPath, $newPath);
                    $newImages[] = $newPath;
                }
            }

            session()->forget('pet_temp_images');
        }

        $pet->update([
            'name'         => $data['name'],
            'slug'         => $slug,
            'species_id'   => $data['species_id'],
            'breed_id'     => $data['breed_id'],
            'age'          => $data['age'],
            'arrival_date' => $data['arrival_date'] ?? $pet->arrival_date,
            'status'       => $data['status']       ?? $pet->status,
            'description'  => $data['description'],
            'images'       => array_merge($currentImages, $newImages),
        ]);

        return redirect()
            ->route('pets.update.index')
            ->with('flash', [
                'message' => 'A kisállat adatai sikeresen frissítve lettek.',
                'type'    => 'success',
            ]);
    }

    public function destroy(Pet $pet)
    {
        $userId = auth()->id();

        $pet->load([
            'shelter:id,name,uuid,owner_id',
        ]);

        $isOwner    = $pet->shelter && $pet->shelter->owner_id === $userId;
        $isEmployee = $pet->employee_id                        === $userId;

        abort_unless($isOwner || $isEmployee, 403);

        $petFolder     = 'pets/' . $pet->shelter->uuid . '/' . $pet->uuid;
        $shelterFolder = 'pets/' . $pet->shelter->uuid;

        if (Storage::disk('public')->exists($petFolder)) {
            Storage::disk('public')->deleteDirectory($petFolder);
        }

        if (
            Storage::disk('public')->exists($shelterFolder) && empty(Storage::disk('public')->directories($shelterFolder)) && empty(Storage::disk('public')->files($shelterFolder))
        ) {
            Storage::disk('public')->deleteDirectory($shelterFolder);
        }

        $pet->delete();

        return redirect()
            ->route('pets.update.index')
            ->with('flash', [
                'message' => 'A kisállat sikeresen törölve lett.',
                'type'    => 'success',
            ]);
    }
}
