<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        return view('auth.registration.shelter.setup');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
        ], [
            'name.required'        => 'A menhely neve kötelező.',
            'name.max'             => 'A menhely neve legfeljebb 255 karakter lehet.',
            'description.required' => 'A leírás megadása kötelező.',
        ]);

        if (array_key_exists('location', $validated)) {
            $location = $validated['location'];
        } else {
            $location = null;
        }

        $shelter = Shelter::create([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'location'    => $location,
            'owner_id'    => auth()->id(),
        ]);

        if (session()->has('shelter_temp_images')) {
            $uploadedImages = [];

            foreach (session('shelter_temp_images') as $tmpImagePath) {
                $filename = basename($tmpImagePath);
                $newPath  = 'shelters/' . $shelter->uuid . '/' . $filename;

                if (Storage::disk('public')->exists($tmpImagePath)) {
                    Storage::disk('public')->move($tmpImagePath, $newPath);
                    $uploadedImages[] = $newPath;
                }
            }

            $shelter->images = $uploadedImages;
            $shelter->save();

            session()->forget('shelter_temp_images');
        }

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

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'required|string',
            'location'        => 'nullable|string|max:255',
            'remove_images'   => ['array'],
            'remove_images.*' => ['string'],
        ], [
            'name.required'        => 'A menhely neve kötelező.',
            'name.max'             => 'A menhely neve legfeljebb 255 karakter lehet.',
            'description.required' => 'A leírás megadása kötelező.',
        ]);

        $existing = $shelter->images_safe;
        $remove   = collect($validated['remove_images'] ?? []);
        $kept     = [];

        foreach ($existing as $imagePath) {
            if (! $remove->contains($imagePath)) {
                $kept[] = $imagePath;
            }
        }
        $newUploaded = [];

        if (session()->has('shelter_temp_images')) {
            foreach (session('shelter_temp_images') as $tmpImagePath) {
                $filename = basename($tmpImagePath);
                $newPath  = 'shelters/' . $shelter->uuid . '/' . $filename;

                if (Storage::disk('public')->exists($tmpImagePath)) {
                    Storage::disk('public')->move($tmpImagePath, $newPath);
                    $newUploaded[] = $newPath;
                }
            }
        }

        $images = array_values(array_unique([...$kept, ...$newUploaded]));

        if (array_key_exists('location', $validated)) {
            $location = $validated['location'];
        } else {
            $location = null;
        }

        $shelter->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
            'location'    => $location,
            'images'      => $images,
        ]);

        session()->forget('shelter_temp_images');

        return redirect()
            ->route('shelters.show', $shelter)
            ->with('flash', [
                'message' => 'A menhely adatai sikeresen frissítve lettek.',
                'type'    => 'success',
            ]);
    }
}
