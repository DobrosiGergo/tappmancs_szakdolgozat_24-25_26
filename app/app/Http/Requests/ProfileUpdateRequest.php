<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'A név megadása kötelező.',
            'name.max'       => 'A név legfeljebb 255 karakter lehet.',
            'email.required' => 'Az e-mail cím megadása kötelező.',
            'email.email'    => 'Érvénytelen e-mail cím.',
            'email.max'      => 'Az e-mail cím legfeljebb 255 karakter lehet.',
            'email.unique'   => 'Ez az e-mail cím már foglalt.',
        ];
    }
}
