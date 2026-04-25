<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'phoneNumber'  => ['nullable', 'string', 'max:15'],
            'role'         => ['required', 'in:shelter,User'],
            'role_shelter' => ['nullable', 'in:shelterOwner,shelterWorker', 'required_if:role,shelter'],
        ]);

        $role        = $validated['role'];
        $roleShelter = $validated['role_shelter'] ?? null;

        $map = [
            'shelterOwner'  => 'Shelterowner',
            'shelterWorker' => 'Shelterworker',
            'User'          => 'User',
        ];

        $type = $role === 'shelter'
            ? ($map[$roleShelter] ?? 'Shelterowner')
            : ($map['User']);

        $user = User::create([
            'type'        => $type,
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'phoneNumber' => $validated['phoneNumber'] ?? null,
        ]);

        Auth::login($user);

        if ($user->type === 'Shelterowner') {
            return redirect()->route('shelter.setup', [
                'role'         => 'shelter',
                'role_shelter' => 'shelterOwner',
            ]);
        }

        event(new Registered($user));

        return redirect()->route('dashboard');
    }
}
