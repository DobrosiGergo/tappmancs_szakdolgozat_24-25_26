<?php

use App\Models\Shelter;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('public pages and public shelter routes are accessible', function () {
get(route('home'))->assertOk();
    get(route('shelters.index'))->assertOk();

    $shelter = Shelter::factory()->create();
    get(route('shelters.show', $shelter))->assertOk();
    });

it('auth-only routes redirect guests to login', function () {
get(route('dashboard'))->assertRedirect(route('login'));

    get(route('settings.index'))->assertRedirect(route('login'));
    get(route('settings.profile'))->assertRedirect(route('login'));
    get(route('settings.password'))->assertRedirect(route('login'));
    get(route('settings.delete'))->assertRedirect(route('login'));

    get(route('shelter.create'))->assertRedirect(route('login'));
    post(route('shelter.store'), [])->assertRedirect(route('login'));
    get(route('shelter.setup'))->assertRedirect(route('login'));
    });

it('authenticated user can access dashboard, settings, shelter create/setup views', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('dashboard'))->assertOk();

    get(route('settings.index'))->assertOk();
    get(route('settings.profile'))->assertOk();
    get(route('settings.password'))->assertOk();
    get(route('settings.delete'))->assertOk();
    get(route('shelter.setup'))->assertOk();
});
