<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Specie;

it('getRouteKeyName returns uuid', function () {
    $specie = new Specie;
    expect($specie->getRouteKeyName())->toBe('uuid');
});

it('selectOptions returns array of value-label pairs for each specie', function () {
    Specie::factory()->create(['name' => 'Kutya']);
    Specie::factory()->create(['name' => 'Macska']);

    $options = Specie::selectOptions();

    expect($options)->toBeArray()->not->toBeEmpty();

    $labels = collect($options)->pluck('label')->all();
    expect($labels)->toContain('Kutya')->toContain('Macska');
});

it('selectOptions returns value as string id', function () {
    $specie = Specie::factory()->create(['name' => 'Hal']);

    $options = Specie::selectOptions();
    $match   = collect($options)->firstWhere('label', 'Hal');

    expect($match)->not->toBeNull()
        ->and($match['value'])->toBe((string) $specie->id);
});

it('has many breeds', function () {
    $specie = Specie::factory()->create();
    $breed  = Breed::factory()->create(['species_id' => $specie->id]);

    expect($specie->breeds)->toHaveCount(1)
        ->and($specie->breeds->first()->is($breed))->toBeTrue();
});

it('has many pets', function () {
    $specie = Specie::factory()->create();
    $pet    = Pet::factory()->create(['species_id' => $specie->id]);

    expect($specie->pets)->toHaveCount(1)
        ->and($specie->pets->first()->is($pet))->toBeTrue();
});
