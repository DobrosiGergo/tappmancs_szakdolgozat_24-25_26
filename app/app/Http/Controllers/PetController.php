<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetStoreRequest;
use App\Models\Pet;
use App\Models\Shelter;
use App\Support\UploadDisk;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function create()
    {
        $shelter = $this->currentShelter();

        return view('pets.create', compact('shelter'));
    }

    public function index(Request $request)
    {
        $query = Pet::query()->with(['shelter:id,name', 'species:id,name', 'breed:id,name']);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                    ->orWhereHas('shelter', function ($sq) use ($term) {
                        $sq->where('location', 'like', '%' . $term . '%');
                    });
            });
        }
        if ($request->filled('species')) {
            $query->where('species_id', $request->species);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->latest();

        $pets = $query->paginate(12);

        return view('pets.index', compact('pets'));
    }

    public function updateIndex()
    {
        $user  = auth()->user();
        $query = Pet::query()->with([
            'shelter:id,name,owner_id',
            'species:id,name',
            'breed:id,name',
        ]);

        if ($user->type === 'Shelterowner') {
            $query->whereHas('shelter', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        } else {
            abort_if(! $user->worksAt, 403);
            $query->where('shelter_id', $user->worksAt->id);
        }

        $pets = $query->latest()->paginate(12);

        return view('pets.update.index', compact('pets'));
    }

    public function updateStatus(Request $request, Pet $pet)
    {
        $this->authorizePet($pet);

        $request->validate(['status' => ['required', 'in:' . implode(',', array_keys(Pet::STATUSES))]]);

        $pet->update(['status' => $request->status]);

        return back()->with('flash', [
            'message' => 'Státusz frissítve.',
            'type'    => 'success',
        ]);
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
        $this->authorizePet($pet);

        $pet->load(['species:id,name', 'breed:id,name']);

        $shelter          = $pet->shelter;
        $birthDateValue   = ($pet->birth_date ?? now())->format('Y-m-d');
        $arrivalDateValue = ($pet->arrival_date ?? now())->format('Y-m-d');

        return view('pets.update.edit', compact('pet', 'shelter', 'birthDateValue', 'arrivalDateValue'));
    }

    public function store(PetStoreRequest $request)
    {
        $data    = $request->validated();
        $shelter = $this->currentShelter();

        $pet = Pet::create([
            'name'         => $data['name'],
            'species_id'   => $data['species_id'],
            'birth_date'   => $data['birth_date'],
            'arrival_date' => $data['arrival_date'],
            'gender'       => $data['gender'],
            'employee_id'  => auth()->id(),
            'shelter_id'   => $shelter->id,
            'status'       => $data['status'] ?? 'free',
            'description'  => $data['description'],
            'images'       => [],
            'breed_id'     => $data['breed_id'],
        ]);

        $pet->update([
            'images' => UploadDisk::moveTempImages('pet', $this->imageFolder($pet)),
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
        $this->authorizePet($pet);

        $data      = $request->validated();
        $newImages = UploadDisk::moveTempImages('pet', $this->imageFolder($pet));

        $pet->update([
            'name'         => $data['name'],
            'species_id'   => $data['species_id'],
            'breed_id'     => $data['breed_id'],
            'birth_date'   => $data['birth_date'],
            'arrival_date' => $data['arrival_date'] ?? $pet->arrival_date,
            'gender'       => $data['gender'],
            'status'       => $data['status'] ?? $pet->status,
            'description'  => $data['description'],
            'images'       => array_merge($pet->images, $newImages),
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
        $this->authorizePet($pet);

        $disk          = UploadDisk::get();
        $petFolder     = $this->imageFolder($pet);
        $shelterFolder = dirname($petFolder);

        $disk->deleteDirectory($petFolder);

        if ($disk->exists($shelterFolder) && empty($disk->allFiles($shelterFolder))) {
            $disk->deleteDirectory($shelterFolder);
        }

        $pet->delete();

        return redirect()
            ->route('pets.update.index')
            ->with('flash', [
                'message' => 'A kisállat sikeresen törölve lett.',
                'type'    => 'success',
            ]);
    }

    /**
     * The shelter the signed-in user adds pets to: their own shelter for an
     * owner, the shelter they work at for a worker.
     */
    private function currentShelter(): Shelter
    {
        $user = auth()->user();

        if ($user->type === 'Shelterowner') {
            return Shelter::where('owner_id', $user->id)->firstOrFail();
        }

        abort_if(! $user->worksAt, 403);

        return $user->worksAt;
    }

    /**
     * Only the owner and the workers of the pet's shelter may change the pet.
     */
    private function authorizePet(Pet $pet): void
    {
        abort_if(! $pet->shelter, 403);

        $this->authorize('managePets', $pet->shelter);
    }

    private function imageFolder(Pet $pet): string
    {
        return 'pets/' . $pet->shelter->uuid . '/' . $pet->uuid;
    }
}
