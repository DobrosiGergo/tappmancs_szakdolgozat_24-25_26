<?php

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('images_safe returns array when images is already an array', function () {
    $pet = Pet::factory()->make(['images' => ['a.jpg', 'b.jpg']]);
    expect($pet->images_safe)->toBe(['a.jpg', 'b.jpg']);
});

it('images_safe returns empty array when images is empty', function () {
    $pet = Pet::factory()->make(['images' => []]);
    expect($pet->images_safe)->toBeArray()->toBeEmpty();
});

it('first_image_url returns null when no images', function () {
    $pet = Pet::factory()->make(['images' => []]);
    expect($pet->first_image_url)->toBeNull();
});

it('first_image_url returns url containing first image path', function () {
    $pet = Pet::factory()->make(['images' => ['pets/some/path.jpg']]);
    expect($pet->first_image_url)->toContain('pets/some/path.jpg');
});

it('first_image_path returns null when no images', function () {
    $pet = Pet::factory()->make(['images' => []]);
    expect($pet->first_image_path)->toBeNull();
});

it('first_image_path returns first element of images array', function () {
    $pet = Pet::factory()->make(['images' => ['pets/a.jpg', 'pets/b.jpg']]);
    expect($pet->first_image_path)->toBe('pets/a.jpg');
});

it('gender_label returns Hím for male', function () {
    $pet = Pet::factory()->make(['gender' => 'male']);
    expect($pet->gender_label)->toBe('Hím');
});

it('gender_label returns Nőstény for female', function () {
    $pet = Pet::factory()->make(['gender' => 'female']);
    expect($pet->gender_label)->toBe('Nőstény');
});

it('gender_label returns Ismeretlen for unknown', function () {
    $pet = Pet::factory()->make(['gender' => 'unknown']);
    expect($pet->gender_label)->toBe('Ismeretlen');
});

it('gender_label returns Ismeretlen for unrecognized value', function () {
    $pet = Pet::factory()->make(['gender' => 'other']);
    expect($pet->gender_label)->toBe('Ismeretlen');
});

it('status_label returns Elérhető for free', function () {
    $pet = Pet::factory()->make(['status' => 'free']);
    expect($pet->status_label)->toBe('Elérhető');
});

it('status_label returns Foglalva for reserved', function () {
    $pet = Pet::factory()->make(['status' => 'reserved']);
    expect($pet->status_label)->toBe('Foglalva');
});

it('status_label returns Örökbefogadott for adopted', function () {
    $pet = Pet::factory()->make(['status' => 'adopted']);
    expect($pet->status_label)->toBe('Örökbefogadott');
});

it('status_badge_class contains bg-neutral-900 for adopted', function () {
    $pet = Pet::factory()->make(['status' => 'adopted']);
    expect($pet->status_badge_class)->toContain('bg-neutral-900');
});

it('status_badge_class contains bg-amber-100 for reserved', function () {
    $pet = Pet::factory()->make(['status' => 'reserved']);
    expect($pet->status_badge_class)->toContain('bg-amber-100');
});

it('status_badge_class contains bg-emerald-100 for free', function () {
    $pet = Pet::factory()->make(['status' => 'free']);
    expect($pet->status_badge_class)->toContain('bg-emerald-100');
});

it('status_class contains bg-neutral-100 for adopted', function () {
    $pet = Pet::factory()->make(['status' => 'adopted']);
    expect($pet->status_class)->toContain('bg-neutral-100');
});

it('status_class contains bg-amber-50 for reserved', function () {
    $pet = Pet::factory()->make(['status' => 'reserved']);
    expect($pet->status_class)->toContain('bg-amber-50');
});

it('status_class contains bg-emerald-50 for free', function () {
    $pet = Pet::factory()->make(['status' => 'free']);
    expect($pet->status_class)->toContain('bg-emerald-50');
});

it('age calculates years from birth_date', function () {
    $pet = Pet::factory()->make(['birth_date' => now()->subYears(3)]);
    expect($pet->age)->toBeFloat()->toBeGreaterThan(2.9)->toBeLessThan(3.1);
});

it('age returns null when birth_date is null', function () {
    $pet = Pet::factory()->make(['birth_date' => null]);
    expect($pet->age)->toBeNull();
});

it('age_label returns string containing év', function () {
    $pet = Pet::factory()->make(['birth_date' => now()->subYears(5)]);
    expect($pet->age_label)->toContain('év');
});

it('age_label returns null when no birth_date', function () {
    $pet = Pet::factory()->make(['birth_date' => null]);
    expect($pet->age_label)->toBeNull();
});

it('excerpt limits description to 120 characters', function () {
    $pet = Pet::factory()->make(['description' => str_repeat('a', 200)]);
    expect(strlen($pet->excerpt))->toBeLessThanOrEqual(123);
});

it('excerpt returns full text when description is under limit', function () {
    $pet = Pet::factory()->make(['description' => 'Rövid leírás.']);
    expect($pet->excerpt)->toBe('Rövid leírás.');
});

it('genderOptions returns array with value and label keys', function () {
    $options = Pet::genderOptions();
    expect($options)->toBeArray()->not->toBeEmpty();
    expect($options[0])->toHaveKeys(['value', 'label']);
});

it('genderOptions covers all GENDERS constants', function () {
    $values = array_column(Pet::genderOptions(), 'value');
    expect($values)->toContain('male')->toContain('female')->toContain('unknown');
});

it('statusOptions returns array with value and label keys', function () {
    $options = Pet::statusOptions();
    expect($options)->toBeArray()->not->toBeEmpty();
    expect($options[0])->toHaveKeys(['value', 'label']);
});

it('statusOptions covers all STATUSES constants', function () {
    $values = array_column(Pet::statusOptions(), 'value');
    expect($values)->toContain('free')->toContain('reserved')->toContain('adopted');
});
