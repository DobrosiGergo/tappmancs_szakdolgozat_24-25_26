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
            'status'       => ['nullable', 'in:adopted,free'],
            'description'  => ['required', 'string', 'min:20'],
            'images'       => ['nullable', 'array', 'max:10'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }
}
