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

it('workers returns users assigned to this shelter', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);

    expect($shelter->workers)->toHaveCount(1)
        ->and($shelter->workers->first()->is($worker))->toBeTrue();
});

it('workers does not include users from other shelters', function () {
    $shelter      = Shelter::factory()->create();
    $otherShelter = Shelter::factory()->create();
    User::factory()->shelterWorker()->create(['shelter_id' => $otherShelter->id]);

    expect($shelter->workers)->toBeEmpty();
});

it('workers returns empty collection when nobody is assigned', function () {
    $shelter = Shelter::factory()->create();

    expect($shelter->workers)->toBeEmpty();
});

it('workers can contain multiple users', function () {
    $shelter  = Shelter::factory()->create();
    $workerA  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    $workerB  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);

    expect($shelter->workers)->toHaveCount(2)
        ->and($shelter->workers->contains($workerA))->toBeTrue()
        ->and($shelter->workers->contains($workerB))->toBeTrue();
});
