<?php

use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;

it('belongs to user and pet and uses the correct table', function () {
    $adoption = Adoption::factory()->create();

    expect($adoption->getTable())->toBe('adoptions');

    expect($adoption->user)->toBeInstanceOf(User::class)
        ->and($adoption->pet)->toBeInstanceOf(Pet::class);
});
