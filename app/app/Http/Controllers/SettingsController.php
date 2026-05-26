<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Helpers\Tools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('auth.settings.index');
    }

    public function editProfile(): View
    {
        return view('auth.settings.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        Tools::flash('Profil sikeresen frissítve.');

        return Redirect::route('settings.profile');
    }

    public function editPassword(): View
    {
        return view('auth.settings.password');
    }

    public function editDelete(): View
    {
        return view('auth.settings.delete');
    }

    public function deleteAccount(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->type === 'Shelterowner') {
            $shelter = $user->shelter;

            if ($shelter) {
                $shelter->workers()->update(['shelter_id' => null]);

                $petShelterFolder = 'pets/' . $shelter->uuid;
                if (Storage::disk('public')->exists($petShelterFolder)) {
                    Storage::disk('public')->deleteDirectory($petShelterFolder);
                }

                $shelterFolder = 'shelters/' . $shelter->uuid;
                if (Storage::disk('public')->exists($shelterFolder)) {
                    Storage::disk('public')->deleteDirectory($shelterFolder);
                }
            }
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
