<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('verification prompt redirects already verified user to dashboard', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('verification.notice'))->assertRedirect(route('dashboard'));
});

it('verification prompt shows verify-email view for unverified user', function () {
    $user = User::factory()->unverified()->create();
    actingAs($user);

    get(route('verification.notice'))->assertOk()->assertViewIs('auth.verify-email');
});

it('email verification marks user as verified and redirects', function () {
    $user = User::factory()->unverified()->create();
    actingAs($user);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id'   => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    get($url)->assertRedirect();

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('email verification redirects without re-verifying if already verified', function () {
    $user = User::factory()->create();
    actingAs($user);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id'   => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    get($url)->assertRedirect();
});

it('resend verification redirects already verified user to dashboard', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('verification.send'))->assertRedirect(route('dashboard'));
});
