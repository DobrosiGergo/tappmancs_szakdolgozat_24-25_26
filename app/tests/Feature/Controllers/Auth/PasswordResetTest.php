<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('reset-password create shows the reset form', function () {
    get(route('password.reset', ['token' => 'test-token']))
        ->assertOk()
        ->assertViewIs('auth.reset-password');
});

it('reset-password store successfully resets password and redirects to login', function () {
    $user  = User::factory()->create(['password' => Hash::make('old-password')]);
    $token = Password::createToken($user);

    post(route('password.store'), [
        'token'                 => $token,
        'email'                 => $user->email,
        'password'              => 'NewPassword1!',
        'password_confirmation' => 'NewPassword1!',
    ])->assertRedirect(route('login'));

    expect(Hash::check('NewPassword1!', $user->fresh()->password))->toBeTrue();
});

it('reset-password store returns validation error for invalid token', function () {
    $user = User::factory()->create();

    post(route('password.store'), [
        'token'                 => 'invalid-token',
        'email'                 => $user->email,
        'password'              => 'NewPassword1!',
        'password_confirmation' => 'NewPassword1!',
    ])->assertSessionHasErrors('email');
});
