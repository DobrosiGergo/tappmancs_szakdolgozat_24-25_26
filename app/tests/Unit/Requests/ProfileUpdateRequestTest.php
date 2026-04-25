<?php

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->user    = User::factory()->create();
    $this->request = new ProfileUpdateRequest;
    $this->request->setUserResolver(fn () => $this->user);
});

it('accepts valid name and email', function () {
    $data = [
        'name'  => 'John Doe',
        'email' => 'john@example.com',
    ];

    $rules = $this->request->rules();
    $v     = Validator::make($data, $rules);

    expect($v->passes())->toBeTrue();
});

it('rejects invalid email format', function () {
    $data = [
        'name'  => 'John Doe',
        'email' => 'not-an-email',
    ];

    $rules = $this->request->rules();
    $v     = Validator::make($data, $rules);

    expect($v->fails())->toBeTrue()
        ->and($v->errors()->has('email'))->toBeTrue();
});

it('rejects too long name', function () {
    $data = [
        'name'  => str_repeat('a', 256),
        'email' => 'long@example.com',
    ];

    $rules = $this->request->rules();
    $v     = Validator::make($data, $rules);

    expect($v->fails())->toBeTrue()
        ->and($v->errors()->has('name'))->toBeTrue();
});

it('rejects duplicate email of another user', function () {
    $other = User::factory()->create(['email' => 'taken@example.com']);

    $data = [
        'name'  => 'John Doe',
        'email' => 'taken@example.com',
    ];

    $rules = $this->request->rules();
    $v     = Validator::make($data, $rules);

    expect($v->fails())->toBeTrue()
        ->and($v->errors()->has('email'))->toBeTrue();
});

it('allows current user to keep their own email', function () {
    $data = [
        'name'  => 'John Doe',
        'email' => $this->user->email,
    ];

    $rules = $this->request->rules();
    $v     = Validator::make($data, $rules);

    expect($v->passes())->toBeTrue();
});
