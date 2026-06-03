<?php

use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('cover_image returns null when images is empty', function () {
    $shelter = Shelter::factory()->make(['images' => []]);
    expect($shelter->cover_image)->toBeNull();
});

it('cover_image returns first image path', function () {
    $shelter = Shelter::factory()->make(['images' => ['shelters/uuid/a.jpg', 'shelters/uuid/b.jpg']]);
    expect($shelter->cover_image)->toBe('shelters/uuid/a.jpg');
});

it('images_safe returns the images array', function () {
    $shelter = Shelter::factory()->make(['images' => ['x.jpg', 'y.jpg']]);
    expect($shelter->images_safe)->toBe(['x.jpg', 'y.jpg']);
});

it('images_safe returns empty array when images is empty', function () {
    $shelter = Shelter::factory()->make(['images' => []]);
    expect($shelter->images_safe)->toBeArray()->toBeEmpty();
});

it('owner_name returns the name of the owning user', function () {
    $owner   = User::factory()->create(['name' => 'Nagy Péter']);
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    expect($shelter->owner_name)->toBe('Nagy Péter');
});

it('excerpt limits description to 120 characters', function () {
    $shelter = Shelter::factory()->make(['description' => str_repeat('b', 200)]);
    expect(strlen($shelter->excerpt))->toBeLessThanOrEqual(123);
});

it('excerpt returns full text when description is under limit', function () {
    $shelter = Shelter::factory()->make(['description' => 'Rövid leírás.']);
    expect($shelter->excerpt)->toBe('Rövid leírás.');
});
