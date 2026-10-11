<?php

use App\Livewire\RegisterForm;
use App\Models\User;
use Livewire\Livewire;

function registerForm(string $role, string $roleShelter = '')
{
    return Livewire::test(RegisterForm::class, ['role' => $role, 'role_shelter' => $roleShelter]);
}

function fillRegisterForm($form, string $name, string $email)
{
    return $form
        ->set('name', $name)
        ->set('email', $email)
        ->set('password', 'Password1!')
        ->set('password_confirmation', 'Password1!');
}

it('validates that name, email, and password are required', function () {
    registerForm('User')
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

it('rejects duplicate email address', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    fillRegisterForm(registerForm('User'), 'Duplicate', 'taken@example.com')
        ->assertSet('emailTaken', true)
        ->call('register')
        ->assertHasErrors('email');
});

it('creates a User type account and redirects to dashboard', function () {
    fillRegisterForm(registerForm('User'), 'Test User', 'testuser@example.com')
        ->call('register')
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
        'type'  => 'User',
    ]);
});

it('creates a Shelterowner account and redirects to shelter setup', function () {
    fillRegisterForm(registerForm('shelter', 'shelterOwner'), 'Shelter Owner', 'owner@example.com')
        ->call('register')
        ->assertRedirect(route('shelter.setup', ['role' => 'shelter', 'role_shelter' => 'shelterOwner']));

    $this->assertDatabaseHas('users', [
        'email' => 'owner@example.com',
        'type'  => 'Shelterowner',
    ]);
});

it('creates a Shelterworker account and redirects to dashboard', function () {
    fillRegisterForm(registerForm('shelter', 'shelterWorker'), 'Shelter Worker', 'worker@example.com')
        ->call('register')
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'worker@example.com',
        'type'  => 'Shelterworker',
    ]);
});
