<?php

use App\Models\Pet;
use App\Models\Shelter;

use function Pest\Laravel\get;

it('home page loads successfully and uses welcome view', function () {
    get(route('home'))->assertOk()->assertViewIs('welcome');
});

it('home page passes count of free pets only', function () {
    Pet::factory()->count(3)->create(['status' => 'free']);
    Pet::factory()->count(2)->create(['status' => 'adopted']);

    get(route('home'))->assertViewHas('petCount', 3);
});

it('home page passes total shelter count', function () {
    Shelter::factory()->count(4)->create();

    get(route('home'))->assertViewHas('shelterCount', 4);
});

it('home page passes at most 4 featured pets', function () {
    Pet::factory()->count(6)->create(['status' => 'free']);

    get(route('home'))->assertViewHas('featuredPets', fn ($pets) => $pets->count() <= 4);
});

it('featured pets only contain pets with free status', function () {
    Pet::factory()->count(3)->create(['status' => 'free']);
    Pet::factory()->count(3)->create(['status' => 'adopted']);

    get(route('home'))->assertViewHas(
        'featuredPets',
        fn ($pets) => $pets->every(fn ($p) => $p->status === 'free')
    );
});

it('home page passes at most 3 featured shelters', function () {
    Shelter::factory()->count(5)->create();

    get(route('home'))->assertViewHas('featuredShelters', fn ($shelters) => $shelters->count() <= 3);
});

it('home page works with empty database', function () {
    get(route('home'))
        ->assertOk()
        ->assertViewHas('petCount', 0)
        ->assertViewHas('shelterCount', 0);
});

it('featured pets collection is empty when no free pets exist', function () {
    Pet::factory()->count(3)->create(['status' => 'adopted']);

    get(route('home'))->assertViewHas('featuredPets', fn ($pets) => $pets->isEmpty());
});
