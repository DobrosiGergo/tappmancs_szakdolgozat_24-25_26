<?php

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
