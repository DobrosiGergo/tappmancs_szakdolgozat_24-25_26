<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('guest can view auth pages (login/register/role/forgot/reset)', function () {
get(route('login'))->assertOk();
    get(route('register', ['role' => 'User']))->assertOk();
    get(route('role'))->assertOk();
    get(route('password.request'))->assertOk();
    get(route('password.reset', ['token' => 'test-token']))->assertOk();
    });

it('guest can hit auth POST endpoints (basic smoke)', function () {
    post(route('registration.store.role'), ['role' => 'User'])->assertStatus(302);
    post(route('password.email'), ['email' => 'none@example.com'])->assertStatus(302);

    post(route('login'), ['email' => 'x@y.z', 'password' => 'bad'])->assertStatus(302);
    post(route('register'), [])->assertStatus(302);
});

it('authenticated can access logout', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('logout'))->assertRedirect('/');
});

it('authenticated can trigger resend verification notification (throttled)', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('verification.send'))->assertStatus(302);
});

it('authenticated can open confirm-password and update password endpoints (smoke)', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('password.confirm'))->assertOk();
    post(route('password.confirm'), ['password' => 'wrong'])->assertStatus(302);
    $response = $this->put(route('password.update'), [
        'current_password'      => 'wrong',
        'password'              => 'new-password',
        'password_confirmation' => 'new-password',
    ]);
    $response->assertStatus(302);
});
