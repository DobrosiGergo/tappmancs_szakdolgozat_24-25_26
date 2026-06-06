<?php

namespace App\Livewire;

use App\Helpers\Tools;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class RegisterForm extends Component
{
    public string $email = '';
    public string $name = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phoneNumber = '';
    public string $role = '';
    public string $role_shelter = '';

    public bool $emailTaken = false;

    public function mount(string $role, string $role_shelter = ''): void
    {
        $this->role         = $role;
        $this->role_shelter = $role_shelter;
    }

    public function updatedEmail(): void
    {
        $email = strtolower(trim($this->email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->emailTaken = User::where('email', $email)->exists();
        } else {
            $this->emailTaken = false;
        }
    }

    public function register(): void
    {
        $this->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'confirmed', Password::defaults()],
            'phoneNumber'  => ['nullable', 'string', 'max:15'],
            'role'         => ['required', 'in:shelter,User'],
            'role_shelter' => ['nullable', 'in:shelterOwner,shelterWorker'],
        ], [
            'name.required'      => 'A név megadása kötelező.',
            'name.max'           => 'A név legfeljebb 255 karakter lehet.',
            'email.required'     => 'Az e-mail cím megadása kötelező.',
            'email.email'        => 'Érvénytelen e-mail cím.',
            'email.unique'       => 'Ez az e-mail cím már foglalt.',
            'password.required'  => 'A jelszó megadása kötelező.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
            'phoneNumber.max'    => 'A telefonszám legfeljebb 15 karakter lehet.',
        ]);

        $map = [
            'shelterOwner'  => 'Shelterowner',
            'shelterWorker' => 'Shelterworker',
        ];

        if ($this->role === 'shelter') {
            if (isset($map[$this->role_shelter])) {
                $type = $map[$this->role_shelter];
            } else {
                $type = 'Shelterowner';
            }
        } else {
            $type = 'User';
        }

        $user = User::create([
            'type'        => $type,
            'name'        => $this->name,
            'email'       => strtolower(trim($this->email)),
            'password'    => Hash::make($this->password),
            'phoneNumber' => $this->phoneNumber ? $this->phoneNumber : null,
        ]);

        Auth::login($user);

        if ($user->type === 'Shelterowner') {
            $this->redirect(route('shelter.setup', [
                'role'         => 'shelter',
                'role_shelter' => 'shelterOwner',
            ]));
            return;
        }

        event(new Registered($user));

        Tools::flash('Sikeres regisztráció, üdvözlünk!');

        $this->redirect(route('dashboard'));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.register-form');
    }
}
