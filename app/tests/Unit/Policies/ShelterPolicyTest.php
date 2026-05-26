<?php

use App\Models\Shelter;
use App\Models\User;
use App\Policies\ShelterPolicy;

$policy = new ShelterPolicy();


it('owner can manage staff', function () {
    $owner   = User::factory()->make(['id' => 1]);
    $shelter = Shelter::factory()->make(['owner_id' => 1]);

    expect((new ShelterPolicy())->manageStaff($owner, $shelter))->toBeTrue();
});

it('non-owner cannot manage staff', function () {
    $user    = User::factory()->make(['id' => 2]);
    $shelter = Shelter::factory()->make(['owner_id' => 1]);

    expect((new ShelterPolicy())->manageStaff($user, $shelter))->toBeFalse();
});

it('manageStaff casts ids to int before comparing', function () {
    $user    = User::factory()->make(['id' => 42]);
    $shelter = Shelter::factory()->make(['owner_id' => '42']);

    expect((new ShelterPolicy())->manageStaff($user, $shelter))->toBeTrue();
});


it('owner can update shelter', function () {
    $owner   = User::factory()->make(['id' => 5]);
    $shelter = Shelter::factory()->make(['owner_id' => 5]);

    expect((new ShelterPolicy())->update($owner, $shelter))->toBeTrue();
});

it('non-owner cannot update shelter', function () {
    $user    = User::factory()->make(['id' => 3]);
    $shelter = Shelter::factory()->make(['owner_id' => 99]);

    expect((new ShelterPolicy())->update($user, $shelter))->toBeFalse();
});

it('update casts ids to int before comparing', function () {
    $user    = User::factory()->make(['id' => 7]);
    $shelter = Shelter::factory()->make(['owner_id' => '7']);

    expect((new ShelterPolicy())->update($user, $shelter))->toBeTrue();
});
