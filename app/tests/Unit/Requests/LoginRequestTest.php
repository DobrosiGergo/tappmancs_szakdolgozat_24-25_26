<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

beforeEach(function () {
    $this->request = new LoginRequest;
    $this->request->merge([
        'email'    => 'john@example.com',
        'password' => 'secret',
        'remember' => false,
    ]);
});

it('validates required email and password', function () {
    $rules = (new LoginRequest)->rules();

    $valid = Validator::make([
        'email'    => 'test@example.com',
        'password' => 'secret',
    ], $rules);
    expect($valid->passes())->toBeTrue();

    $invalid = Validator::make([
        'email' => 'test@example.com',
    ], $rules);
    expect($invalid->fails())->toBeTrue()
        ->and($invalid->errors()->has('password'))->toBeTrue();

    $invalidEmail = Validator::make([
        'email'    => 'not-an-email',
        'password' => 'secret',
    ], $rules);
    expect($invalidEmail->fails())->toBeTrue()
        ->and($invalidEmail->errors()->has('email'))->toBeTrue();
});

it('authenticates with correct credentials', function () {
    $user = User::factory()->create([
        'email'    => 'john@example.com',
        'password' => bcrypt('secret'),
    ]);

    $this->request->merge([
        'email'    => 'john@example.com',
        'password' => 'secret',
    ]);

    $this->request->authenticate();

    expect(Auth::check())->toBeTrue()
        ->and(Auth::user()->id)->toBe($user->id);
});

it('fails authentication with wrong password', function () {
    $user = User::factory()->create([
        'email'    => 'john@example.com',
        'password' => bcrypt('secret'),
    ]);

    $this->request->merge([
        'email'    => 'john@example.com',
        'password' => 'wrong',
    ]);

    expect(fn () => $this->request->authenticate())
        ->toThrow(ValidationException::class);

    expect(Auth::check())->toBeFalse();
});

it('is rate limited after too many failed attempts', function () {
    RateLimiter::clear($this->request->throttleKey());

    for ($i = 0; $i < 6; $i++) {
        RateLimiter::hit($this->request->throttleKey());
    }

    expect(fn () => $this->request->ensureIsNotRateLimited())
        ->toThrow(ValidationException::class);
});
