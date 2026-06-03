<?php

use App\Http\Requests\PetStoreRequest;
use App\Models\Breed;
use App\Models\Specie;
use Illuminate\Support\Facades\Validator;

function petBase(): array
{
    $species = Specie::factory()->create();
    $breed   = Breed::factory()->create(['species_id' => $species->id]);

    return [
        'name'         => 'Bodri',
        'species_id'   => $species->id,
        'breed_id'     => $breed->id,
        'birth_date'   => now()->subYears(2)->toDateString(),
        'arrival_date' => now()->subMonths(3)->toDateString(),
        'gender'       => 'male',
        'description'  => str_repeat('a', 25),
        'status'       => 'free',
    ];
}

it('passes with all valid fields', function () {
    $v = Validator::make(petBase(), (new PetStoreRequest)->rules());
    expect($v->passes())->toBeTrue();
});

it('requires name', function () {
    $v = Validator::make(array_merge(petBase(), ['name' => '']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('name'))->toBeTrue();
});

it('rejects name shorter than 2 characters', function () {
    $v = Validator::make(array_merge(petBase(), ['name' => 'A']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('name'))->toBeTrue();
});

it('rejects name longer than 255 characters', function () {
    $v = Validator::make(array_merge(petBase(), ['name' => str_repeat('x', 256)]), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('name'))->toBeTrue();
});

it('requires species_id that exists in database', function () {
    $v = Validator::make(array_merge(petBase(), ['species_id' => 99999]), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('species_id'))->toBeTrue();
});

it('requires breed_id that exists in database', function () {
    $v = Validator::make(array_merge(petBase(), ['breed_id' => 99999]), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('breed_id'))->toBeTrue();
});

it('rejects future birth_date', function () {
    $v = Validator::make(
        array_merge(petBase(), ['birth_date' => now()->addDay()->toDateString()]),
        (new PetStoreRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('birth_date'))->toBeTrue();
});

it('rejects birth_date more than 30 years ago', function () {
    $v = Validator::make(
        array_merge(petBase(), ['birth_date' => now()->subYears(31)->toDateString()]),
        (new PetStoreRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('birth_date'))->toBeTrue();
});

it('requires gender from allowed values', function () {
    $v = Validator::make(array_merge(petBase(), ['gender' => 'invalid']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('gender'))->toBeTrue();
});

it('accepts all three valid gender values', function () {
    foreach (['male', 'female', 'unknown'] as $gender) {
        $v = Validator::make(array_merge(petBase(), ['gender' => $gender]), (new PetStoreRequest)->rules());
        expect($v->passes())->toBeTrue();
    }
});

it('rejects arrival_date before birth_date', function () {
    $v = Validator::make(array_merge(petBase(), [
        'birth_date'   => now()->subYears(1)->toDateString(),
        'arrival_date' => now()->subYears(2)->toDateString(),
    ]), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('arrival_date'))->toBeTrue();
});

it('rejects future arrival_date', function () {
    $v = Validator::make(
        array_merge(petBase(), ['arrival_date' => now()->addDay()->toDateString()]),
        (new PetStoreRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('arrival_date'))->toBeTrue();
});

it('requires description', function () {
    $v = Validator::make(array_merge(petBase(), ['description' => '']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('description'))->toBeTrue();
});

it('rejects description shorter than 20 characters', function () {
    $v = Validator::make(array_merge(petBase(), ['description' => 'Short desc.']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('description'))->toBeTrue();
});

it('rejects invalid status value', function () {
    $v = Validator::make(array_merge(petBase(), ['status' => 'unknown_value']), (new PetStoreRequest)->rules());
    expect($v->fails())->toBeTrue()->and($v->errors()->has('status'))->toBeTrue();
});

it('accepts valid status values', function () {
    foreach (['free', 'reserved', 'adopted'] as $status) {
        $v = Validator::make(array_merge(petBase(), ['status' => $status]), (new PetStoreRequest)->rules());
        expect($v->passes())->toBeTrue();
    }
});

it('status is optional', function () {
    $data = petBase();
    unset($data['status']);
    $v = Validator::make($data, (new PetStoreRequest)->rules());
    expect($v->passes())->toBeTrue();
});
