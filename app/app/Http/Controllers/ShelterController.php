<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Support\UploadDisk;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function setup()
    {
        return view('auth.registration.shelter.setup');
    }

    public function index(Request $request)
    {
        $query = Shelter::with('owner')->withCount('pets');

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhere('location', 'like', '%' . $term . '%');
            });
        }

        $shelters = $query->paginate(20);

        return view('shelters.index', compact('shelters'));
    }

    public function show(Shelter $shelter)
    {
        $shelter->load('owner');
        $pets     = $shelter->pets()->latest()->paginate(4);
        $petCount = $pets->total();

        return view('shelters.show', compact('shelter', 'pets', 'petCount'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateShelter($request);

        $shelter = Shelter::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'location'    => $validated['location'] ?? null,
            'owner_id'    => auth()->id(),
        ]);

        $shelter->update([
            'images' => UploadDisk::moveTempImages('shelter', $this->imageFolder($shelter)),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('flash', [
                'message' => 'A menhely sikeresen létrejött.',
                'type'    => 'success',
            ]);
    }

    public function edit(Shelter $shelter)
    {
        $this->authorize('update', $shelter);

        return view('auth.registration.shelter.setup', [
            'shelter' => $shelter,
            'mode'    => 'edit',
        ]);
    }

    public function update(Request $request, Shelter $shelter)
    {
        $this->authorize('update', $shelter);

        $validated = $this->validateShelter($request, [
            'remove_images'   => ['array'],
            'remove_images.*' => ['string'],
        ]);

        $kept        = array_diff($shelter->images_safe, $validated['remove_images'] ?? []);
        $newUploaded = UploadDisk::moveTempImages('shelter', $this->imageFolder($shelter));

        $shelter->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'location'    => $validated['location'] ?? null,
            'images'      => array_values(array_unique([...$kept, ...$newUploaded])),
        ]);

        return redirect()
            ->route('shelters.show', $shelter)
            ->with('flash', [
                'message' => 'A menhely adatai sikeresen frissítve lettek.',
                'type'    => 'success',
            ]);
    }

    private function validateShelter(Request $request, array $extraRules = []): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            ...$extraRules,
        ], [
            'name.required'        => 'A menhely neve kötelező.',
            'name.max'             => 'A menhely neve legfeljebb 255 karakter lehet.',
            'description.required' => 'A leírás megadása kötelező.',
        ]);
    }

    private function imageFolder(Shelter $shelter): string
    {
        return 'shelters/' . $shelter->uuid;
    }
}
