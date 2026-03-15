<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Models\Pet;
use App\Models\Shelter;
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

    public function store(PetStoreRequest $request)
    {
        $data    = $request->validated();
        $shelter = Shelter::where('owner_id', auth()->id())->firstOrFail();

        $slug = Str::slug($data['name']);

        $paths = [];
        if (session()->has('pet_temp_images')) {
            foreach (session('pet_temp_images') as $tmpPath) {
                $filename = basename($tmpPath);
                $newPath  = 'pets/' . $shelter->uuid . '/' . $filename;

                if (\Storage::disk('public')->exists($tmpPath)) {
                    \Storage::disk('public')->move($tmpPath, $newPath);
                    $paths[] = $newPath;
                }
            }
            session()->forget('pet_temp_images');
        }

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
            'images'       => $paths,
            'breed_id'     => $data['breed_id'],
        ]);

        return redirect()->route('pets.show', $pet)->with('success', 'Kisállat létrehozva.');
    }
}
