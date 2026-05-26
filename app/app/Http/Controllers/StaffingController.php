<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Models\User;
use App\Notifications\WorkerAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffingController extends Controller
{
    public function index(Shelter $shelter)
    {
        $this->authorize('manageStaff', $shelter);

        $workers = $shelter->workers()->get();

        return view('shelters.staffing.index', compact('shelter', 'workers'));
    }

    public function store(Request $request, Shelter $shelter)
    {
        $this->authorize('manageStaff', $shelter);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Nem található felhasználó ezzel az e-mail címmel.']);
        }

        if ($user->id === $shelter->owner_id) {
            return back()->withErrors(['email' => 'A menhely tulajdonosa nem adható hozzá munkatársként.']);
        }

        if (! in_array($user->type, ['User', 'Shelterworker'], true)) {
            return back()->withErrors(['email' => 'Ez a felhasználó nem adható hozzá munkatársként.']);
        }

        if ($user->shelter_id === $shelter->id) {
            return back()->withErrors(['email' => 'Ez a felhasználó már munkatársa a menhelynek.']);
        }

        $user->update([
            'type'       => 'Shelterworker',
            'shelter_id' => $shelter->id,
        ]);

        $user->notify(new WorkerAssigned($shelter));

        return back()->with('flash', [
            'message' => "{$user->name} sikeresen hozzáadva munkatársként.",
            'type'    => 'success',
        ]);
    }

    public function destroy(Shelter $shelter, User $worker)
    {
        $this->authorize('manageStaff', $shelter);

        if ($worker->shelter_id !== $shelter->id) {
            abort(403);
        }

        $worker->update(['shelter_id' => null]);

        return back()->with('flash', [
            'message' => "{$worker->name} eltávolítva a munkatársak közül.",
            'type'    => 'success',
        ]);
    }

    public function leave()
    {
        $user = Auth::user();

        if ($user->shelter_id === null) {
            return back()->with('flash', [
                'message' => 'Nem vagy hozzárendelve egyetlen menhelyhez sem.',
                'type'    => 'error',
            ]);
        }

        $user->update(['shelter_id' => null]);

        return redirect()->route('dashboard')->with('flash', [
            'message' => 'Sikeresen elhagytad a menhelyet.',
            'type'    => 'success',
        ]);
    }
}
