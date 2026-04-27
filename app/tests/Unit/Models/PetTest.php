<?php

use App\Models\Adoption;
use App\Models\Breed;
use App\Models\Comment;
use App\Models\Form;
use App\Models\Like;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses custom table and has default attributes', function () {
    $pet = new Pet;

    expect($pet->getTable())->toBe('pets')
        ->and($pet->status)->toBe('free')
        ->and($pet->images)->toBe([]);
});

it('belongs to a shelter', function () {
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);

    expect($pet->shelter->id)->toBe($shelter->id);
});

it('belongs to a species', function () {
    $species = Specie::factory()->create();
    $pet     = Pet::factory()->create(['species_id' => $species->id]);

    expect($pet->species->id)->toBe($species->id);
});

it('belongs to a breed', function () {
    $breed = Breed::factory()->create();
    $pet   = Pet::factory()->create(['breed_id' => $breed->id]);

    expect($pet->breed->id)->toBe($breed->id);
});

it('belongs to an employee user', function () {
    $user = User::factory()->create();
    $pet  = Pet::factory()->create(['employee_id' => $user->id]);

    expect($pet->employee->id)->toBe($user->id);
});

it('has many comments', function () {
    $pet = Pet::factory()->create();
    Comment::factory()->count(2)->create(['pet_id' => $pet->id]);

    expect($pet->comments)->toHaveCount(2);
});

it('has many likes', function () {
    $pet = Pet::factory()->create();
    Like::factory()->count(3)->create(['pet_id' => $pet->id]);

    expect($pet->likes)->toHaveCount(3);
});

it('has one adoption', function () {
    $pet = Pet::factory()->create();
    Adoption::factory()->create(['pet_id' => $pet->id]);

    expect($pet->adoption)->not->toBeNull();
});

it('has many form messages', function () {
    $pet = Pet::factory()->create();
    Form::factory()->count(2)->create(['pet_id' => $pet->id]);

    expect($pet->formMessages)->toHaveCount(2);
});
