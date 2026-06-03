<?php

use App\Http\Requests\ShelterRequest;
use Illuminate\Support\Facades\Validator;

it('passes with valid name and description', function () {
    $v = Validator::make(
        ['name' => 'Pesti Menhely', 'description' => 'Egy hosszabb leírás a menhelyről.'],
        (new ShelterRequest)->rules()
    );
    expect($v->passes())->toBeTrue();
});

it('requires name', function () {
    $v = Validator::make(
        ['name' => '', 'description' => 'Leírás'],
        (new ShelterRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('name'))->toBeTrue();
});

it('rejects name longer than 255 characters', function () {
    $v = Validator::make(
        ['name' => str_repeat('n', 256), 'description' => 'Leírás'],
        (new ShelterRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('name'))->toBeTrue();
});

it('requires description', function () {
    $v = Validator::make(
        ['name' => 'Pesti Menhely', 'description' => ''],
        (new ShelterRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('description'))->toBeTrue();
});

it('remove_images must be an array when present', function () {
    $v = Validator::make(
        ['name' => 'Pesti Menhely', 'description' => 'Leírás', 'remove_images' => 'not-an-array'],
        (new ShelterRequest)->rules()
    );
    expect($v->fails())->toBeTrue()->and($v->errors()->has('remove_images'))->toBeTrue();
});

it('accepts remove_images as array of strings', function () {
    $v = Validator::make(
        ['name' => 'Pesti Menhely', 'description' => 'Leírás', 'remove_images' => ['a.jpg', 'b.jpg']],
        (new ShelterRequest)->rules()
    );
    expect($v->passes())->toBeTrue();
});

it('remove_images is optional', function () {
    $v = Validator::make(
        ['name' => 'Pesti Menhely', 'description' => 'Leírás'],
        (new ShelterRequest)->rules()
    );
    expect($v->passes())->toBeTrue();
});

it('authorize always returns true', function () {
    expect((new ShelterRequest)->authorize())->toBeTrue();
});
