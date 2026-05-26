<?php

use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;

it('casts email_verified_at and password properly', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('has one shelter via owner_id', function () {
    $user    = User::factory()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $user->id]);

    expect($user->shelter?->is($shelter))->toBeTrue();
});

it('has many pets as employee', function () {
    $user = User::factory()->create();
    $pet  = Pet::factory()->create(['employee_id' => $user->id]);

    expect($user->pets)->toHaveCount(1)
        ->and($user->pets->first()->is($pet))->toBeTrue();
});

it('worksAt returns the shelter when shelter_id is set', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);

    expect($worker->worksAt->is($shelter))->toBeTrue();
});

it('worksAt returns null when shelter_id is not set', function () {
    $worker = User::factory()->shelterWorker()->create(['shelter_id' => null]);

    expect($worker->worksAt)->toBeNull();
});

it('shelterworker factory state sets type to Shelterworker', function () {
    $user = User::factory()->shelterWorker()->make();

    expect($user->type)->toBe('Shelterworker');
});
