<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Shelter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'shelter_id' => ['required', 'integer', 'exists:shelter,id'],
            'pet_id'     => ['nullable', 'integer', 'exists:pets,id'],
            'message'    => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'shelter_id.required' => 'A menhely azonosítója hiányzik.',
            'shelter_id.exists'   => 'A menhely nem található.',
            'pet_id.exists'       => 'A kisállat nem található.',
            'message.required'    => 'Az üzenet megadása kötelező.',
            'message.min'         => 'Az üzenet legalább 10 karakter kell legyen.',
            'message.max'         => 'Az üzenet legfeljebb 2000 karakter lehet.',
        ]);

        $shelter = Shelter::findOrFail($request->shelter_id);

        if ($request->pet_id && Form::where('user_id', Auth::id())->where('pet_id', $request->pet_id)->exists()) {
            return back()->withErrors(['message' => 'Már küldtél üzenetet ezzel a kisállattal kapcsolatban.']);
        }

        $petId = null;
        if ($request->pet_id) {
            $petId = $request->pet_id;
        }

        if ($request->pet_id) {
            $subject = 'Érdeklődés kisállattal kapcsolatban – ' . $shelter->name;
        } else {
            $subject = 'Üzenet – ' . $shelter->name;
        }

        Form::create([
            'user_id'    => Auth::id(),
            'shelter_id' => $shelter->id,
            'pet_id'     => $petId,
            'subject'    => $subject,
            'message'    => $request->message,
        ]);

        return back()->with('flash', [
            'message' => 'Üzeneted sikeresen elküldve! A menhely koordinátora hamarosan jelentkezik.',
            'type'    => 'success',
        ]);
    }

    public function index()
    {
        $shelter = Auth::user()->shelter;

        if (! $shelter) {
            return redirect()->route('shelter.setup');
        }

        $messages = $shelter->formMessages()
            ->with(['user', 'pet'])
            ->latest()
            ->paginate(20);

        return view('messages.index', compact('shelter', 'messages'));
    }

    public function show(Form $form)
    {
        $shelter = Auth::user()->shelter;

        if (! $shelter || $form->shelter_id !== $shelter->id) {
            abort(403);
        }

        $form->load(['user', 'pet']);

        return view('messages.show', compact('form', 'shelter'));
    }

    public function destroy(Form $form)
    {
        $shelter = Auth::user()->shelter;

        if (! $shelter || $form->shelter_id !== $shelter->id) {
            abort(403);
        }

        $form->delete();

        return redirect()->route('messages.index')->with('flash', [
            'message' => 'Üzenet törölve.',
            'type'    => 'success',
        ]);
    }
}
