<?php

use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;

it('uses custom table and defaults images to []', function () {
    $shelter = new Shelter;
    expect($shelter->getTable())->toBe('shelter')
        ->and($shelter->images)->toBeArray()->toBe([]);
});

it('belongs to an owning user', function () {
    $owner   = User::factory()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);

    expect($shelter->owner->is($owner))->toBeTrue();
});

it('has many pets', function () {
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);

    expect($shelter->pets)->toHaveCount(1)
        ->and($shelter->pets->first()->is($pet))->toBeTrue();
});
