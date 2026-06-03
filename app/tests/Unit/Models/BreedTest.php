<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Specie;

it('getRouteKeyName returns uuid', function () {
    $breed = new \App\Models\Breed;
    expect($breed->getRouteKeyName())->toBe('uuid');
});

it('has pets relation', function () {
    $breed = Breed::factory()->create([
        'species_id' => Specie::factory()->create()->id,
    ]);

    Pet::factory()->count(2)->create(['breed_id' => $breed->id]);

    expect($breed->pets)->toHaveCount(2);
});
