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
