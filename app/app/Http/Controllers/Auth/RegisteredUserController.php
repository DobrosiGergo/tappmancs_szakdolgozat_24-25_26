<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function showRole(): View
    {
        return view('auth.registration.role_selection');
    }

    public function showShelterRole(Request $request): View|RedirectResponse
    {
        if ($request->query('role') !== 'shelter') {
            return redirect()->route('role');
        }

        return view('auth.registration.shelter.role_selection', ['role' => 'shelter']);
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->filled('role')) {
            return redirect()->route('role');
        }
        if ($request->query('role') === 'shelter' && ! $request->filled('role_shelter')) {
            return redirect()->route('registration.shelter.role_selection', ['role' => 'shelter']);
        }

        return view('auth.registration.register', [
            'role'         => $request->query('role'),
            'role_shelter' => $request->query('role_shelter'),
        ]);
    }

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:shelter,User',
        ]);

        if ($validated['role'] === 'shelter') {
            return redirect()->route('registration.shelter.role_selection', ['role' => 'shelter']);
        }

        return redirect()->route('register', ['role' => 'User']);
    }

    public function storeShelterRole(Request $request)
    {
        $validated = $request->validate([
            'role_shelter' => 'required|in:shelterOwner,shelterWorker',
        ]);

        return redirect()->route('register', [
            'role'         => 'shelter',
            'role_shelter' => $validated['role_shelter'],
        ]);
    }
}
