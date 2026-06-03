<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('showShelterRole redirects to role when role query param is absent', function () {
    get(route('registration.shelter.role_selection'))->assertRedirect(route('role'));
});

it('showShelterRole returns shelter role view when role is shelter', function () {
    get(route('registration.shelter.role_selection', ['role' => 'shelter']))->assertOk();
});

it('create redirects to role when no role param is provided', function () {
    get(route('register'))->assertRedirect(route('role'));
});

it('create redirects to shelter role selection when role is shelter but role_shelter is missing', function () {
    get(route('register', ['role' => 'shelter']))
        ->assertRedirect(route('registration.shelter.role_selection', ['role' => 'shelter']));
});

it('create shows register view when role and role_shelter are provided', function () {
    get(route('register', ['role' => 'shelter', 'role_shelter' => 'shelterOwner']))->assertOk();
});

it('storeRole redirects to shelter role selection when role is shelter', function () {
    post(route('registration.store.role'), ['role' => 'shelter'])
        ->assertRedirect(route('registration.shelter.role_selection', ['role' => 'shelter']));
});

it('storeRole redirects to register when role is User', function () {
    post(route('registration.store.role'), ['role' => 'User'])
        ->assertRedirect(route('register', ['role' => 'User']));
});

it('storeShelterRole validates that role_shelter is required', function () {
    post(route('registration.shelter.role_selection_store'), [])
        ->assertSessionHasErrors('role_shelter');
});

it('storeShelterRole redirects to register with correct params', function () {
    post(route('registration.shelter.role_selection_store'), ['role_shelter' => 'shelterOwner'])
        ->assertRedirect(route('register', ['role' => 'shelter', 'role_shelter' => 'shelterOwner']));
});

it('store validates that name, email, and password are required', function () {
    post(route('register'), ['role' => 'User'])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

it('store rejects duplicate email address', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    post(route('register'), [
        'name'                  => 'Duplicate',
        'email'                 => 'taken@example.com',
        'password'              => 'Password1!',
        'password_confirmation' => 'Password1!',
        'role'                  => 'User',
    ])->assertSessionHasErrors('email');
});

it('store creates a User type account and redirects to dashboard', function () {
    post(route('register'), [
        'name'                  => 'Test User',
        'email'                 => 'testuser@example.com',
        'password'              => 'Password1!',
        'password_confirmation' => 'Password1!',
        'role'                  => 'User',
    ])->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
        'type'  => 'User',
    ]);
});

it('store creates a Shelterowner account and redirects to shelter setup', function () {
    post(route('register'), [
        'name'                  => 'Shelter Owner',
        'email'                 => 'owner@example.com',
        'password'              => 'Password1!',
        'password_confirmation' => 'Password1!',
        'role'                  => 'shelter',
        'role_shelter'          => 'shelterOwner',
    ])->assertRedirect(route('shelter.setup', ['role' => 'shelter', 'role_shelter' => 'shelterOwner']));

    $this->assertDatabaseHas('users', [
        'email' => 'owner@example.com',
        'type'  => 'Shelterowner',
    ]);
});

it('store creates a Shelterworker account and redirects to dashboard', function () {
    post(route('register'), [
        'name'                  => 'Shelter Worker',
        'email'                 => 'worker@example.com',
        'password'              => 'Password1!',
        'password_confirmation' => 'Password1!',
        'role'                  => 'shelter',
        'role_shelter'          => 'shelterWorker',
    ])->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'worker@example.com',
        'type'  => 'Shelterworker',
    ]);
});
