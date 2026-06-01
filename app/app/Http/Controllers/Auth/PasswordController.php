<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Tools;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required'         => 'A jelenlegi jelszó megadása kötelező.',
            'current_password.current_password' => 'A jelenlegi jelszó helytelen.',
            'password.required'                 => 'Az új jelszó megadása kötelező.',
            'password.confirmed'                => 'A két jelszó nem egyezik.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Tools::flash('Jelszó sikeresen megváltoztatva.');

        return back();
    }
}
