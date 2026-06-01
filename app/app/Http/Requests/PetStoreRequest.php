<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'min:2', 'max:255'],
            'species_id'   => ['required', 'exists:species,id'],
            'breed_id'     => ['required', 'exists:breeds,id'],
            'birth_date'   => ['required', 'date', 'before_or_equal:today', 'after:' . now()->subYears(30)->toDateString()],
            'gender'       => ['required', 'in:male,female,unknown'],
            'arrival_date' => ['required', 'date', 'before_or_equal:today', 'after_or_equal:birth_date'],
            'status'       => ['nullable', 'in:adopted,free,reserved'],
            'description'  => ['required', 'string', 'min:20'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*'     => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                => 'A kisállat neve kötelező.',
            'name.min'                     => 'A kisállat neve legalább 2 karakter legyen.',
            'name.max'                     => 'A kisállat neve legfeljebb 255 karakter lehet.',
            'species_id.required'          => 'A faj megadása kötelező.',
            'species_id.exists'            => 'A kiválasztott faj nem érvényes.',
            'breed_id.required'            => 'A fajta megadása kötelező.',
            'breed_id.exists'              => 'A kiválasztott fajta nem érvényes.',
            'birth_date.required'          => 'A születési dátum megadása kötelező.',
            'birth_date.date'              => 'Érvénytelen születési dátum.',
            'birth_date.before_or_equal'   => 'A születési dátum nem lehet jövőbeli.',
            'birth_date.after'             => 'A születési dátum legfeljebb 30 évvel ezelőtti lehet.',
            'gender.required'              => 'A nem megadása kötelező.',
            'gender.in'                    => 'Érvénytelen nem érték.',
            'arrival_date.required'        => 'Az érkezés dátumának megadása kötelező.',
            'arrival_date.date'            => 'Érvénytelen érkezési dátum.',
            'arrival_date.before_or_equal' => 'Az érkezési dátum nem lehet jövőbeli.',
            'arrival_date.after_or_equal'  => 'Az érkezési dátum nem lehet korábbi, mint a születési dátum.',
            'status.in'                    => 'Érvénytelen státusz érték.',
            'description.required'         => 'A leírás megadása kötelező.',
            'description.min'              => 'A leírás legalább 20 karakter kell legyen.',
            'images.max'                   => 'Legfeljebb 10 képet tölthetsz fel.',
            'images.*.image'               => 'Csak képfájlokat tölthetsz fel.',
            'images.*.mimes'               => 'Csak JPEG és PNG képek engedélyezettek.',
            'images.*.max'                 => 'Minden kép legfeljebb 4 MB lehet.',
        ];
    }
}
