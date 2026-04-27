<?php

use App\Models\Like;
use App\Models\Pet;
use App\Models\User;

it('belongs to user and pet', function () {
    $like = Like::factory()->create();

    expect($like->user)->toBeInstanceOf(User::class)
        ->and($like->pet)->toBeInstanceOf(Pet::class);
});

it('enforces unique user-pet pair if DB constraint exists', function () {
    $user = User::factory()->create();
    $pet  = Pet::factory()->create();

    Like::factory()->create(['user_id' => $user->id, 'pet_id' => $pet->id]);

    expect(fn () => Like::factory()->create(['user_id' => $user->id, 'pet_id' => $pet->id]))
        ->toThrow(\Illuminate\Database\QueryException::class);
})->skip(fn () => false, 'Vedd le a skip-et, ha a likes táblán beállítottad az egyedi indexet (user_id, pet_id).');
