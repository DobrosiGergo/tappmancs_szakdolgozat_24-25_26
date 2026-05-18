<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Shelter;

class WelcomeController extends Controller
{
    public function index()
    {
        $petCount = Pet::where('status', 'free')->count();
        $shelterCount = Shelter::count();

        $featuredPets = Pet::where('status', 'free')
            ->with(['shelter' ,'breed'])
            ->latest()
            ->take(4)
            ->get();

        $featuredShelters = Shelter::withCount('pets')
            ->with('owner')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('petCount', 'shelterCount', 'featuredPets', 'featuredShelters'));
    }
}
