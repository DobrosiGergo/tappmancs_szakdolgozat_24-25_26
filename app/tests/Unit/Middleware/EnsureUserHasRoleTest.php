<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

// ─── helper ──────────────────────────────────────────────────────────────────

function makeRequest(?User $user): Request
{
    $request = Request::create('/test');
    $request->setUserResolver(fn () => $user);

    return $request;
}

// ─── passing cases ────────────────────────────────────────────────────────────

it('allows request through when user has the required role', function () {
    $user    = User::factory()->make(['type' => 'Shelterworker']);
    $request = makeRequest($user);

    $called = false;
    (new EnsureUserHasRole())->handle($request, function () use (&$called) {
        $called = true;

        return response('ok');
    }, 'Shelterworker');

    expect($called)->toBeTrue();
});

it('allows request through when user matches one of multiple accepted roles', function () {
    $user    = User::factory()->make(['type' => 'Shelterowner']);
    $request = makeRequest($user);

    $called = false;
    (new EnsureUserHasRole())->handle($request, function () use (&$called) {
        $called = true;

        return response('ok');
    }, 'Shelterowner', 'Shelterworker');

    expect($called)->toBeTrue();
});

it('allows Shelterworker when both shelter roles are accepted', function () {
    $user    = User::factory()->shelterWorker()->make();
    $request = makeRequest($user);

    $called = false;
    (new EnsureUserHasRole())->handle($request, function () use (&$called) {
        $called = true;

        return response('ok');
    }, 'Shelterowner', 'Shelterworker');

    expect($called)->toBeTrue();
});

// ─── blocking cases ───────────────────────────────────────────────────────────

it('aborts with 403 when user has wrong role', function () {
    $user    = User::factory()->make(['type' => 'User']);
    $request = makeRequest($user);

    try {
        (new EnsureUserHasRole())->handle($request, fn () => null, 'Shelterworker');
        expect(false)->toBeTrue('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

it('aborts with 403 when no user is authenticated', function () {
    $request = makeRequest(null);

    try {
        (new EnsureUserHasRole())->handle($request, fn () => null, 'Shelterworker');
        expect(false)->toBeTrue('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

it('does not allow empty roles list (no role matches)', function () {
    $user    = User::factory()->make(['type' => 'Shelterworker']);
    $request = makeRequest($user);

    try {
        (new EnsureUserHasRole())->handle($request, fn () => null);
        expect(false)->toBeTrue('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

// ─── strict matching ─────────────────────────────────────────────────────────

it('role check is case-sensitive (strict)', function () {
    $user    = User::factory()->make(['type' => 'shelterworker']);
    $request = makeRequest($user);

    try {
        (new EnsureUserHasRole())->handle($request, fn () => null, 'Shelterworker');
        expect(false)->toBeTrue('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});

it('does not coerce integer-like role strings', function () {
    $user    = User::factory()->make(['type' => '1']);
    $request = makeRequest($user);

    try {
        (new EnsureUserHasRole())->handle($request, fn () => null, 'Shelterworker');
        expect(false)->toBeTrue('Expected HttpException was not thrown');
    } catch (HttpException $e) {
        expect($e->getStatusCode())->toBe(403);
    }
});
