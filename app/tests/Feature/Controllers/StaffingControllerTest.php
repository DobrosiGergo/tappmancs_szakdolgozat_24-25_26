<?php

use App\Models\Shelter;
use App\Models\User;
use App\Notifications\WorkerAssigned;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

// ─── index ────────────────────────────────────────────────────────────────────

it('guest cannot view staffing page', function () {
    $shelter = Shelter::factory()->create();

    get(route('shelter.staffing.index', $shelter))->assertRedirect(route('login'));
});

it('non-owner cannot view staffing page', function () {
    $shelter = Shelter::factory()->create();
    $other   = User::factory()->create();
    actingAs($other);

    get(route('shelter.staffing.index', $shelter))->assertForbidden();
});

it('owner sees staffing page and workers are passed to the view', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    get(route('shelter.staffing.index', $shelter))
        ->assertOk()
        ->assertViewIs('shelters.staffing.index')
        ->assertViewHas('workers', fn ($ws) => $ws->contains($worker));
});

it('workers of other shelters do not appear on the staffing page', function () {
    $owner    = User::factory()->shelterOwner()->create();
    $shelter  = Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherShelter = Shelter::factory()->create();
    $stranger = User::factory()->shelterWorker()->create(['shelter_id' => $otherShelter->id]);
    actingAs($owner);

    get(route('shelter.staffing.index', $shelter))
        ->assertViewHas('workers', fn ($ws) => ! $ws->contains($stranger));
});

// ─── store ────────────────────────────────────────────────────────────────────

it('guest cannot add worker', function () {
    $shelter = Shelter::factory()->create();

    post(route('shelter.staffing.store', $shelter), [])->assertRedirect(route('login'));
});

it('non-owner cannot add worker', function () {
    $shelter = Shelter::factory()->create();
    $other   = User::factory()->create();
    actingAs($other);

    post(route('shelter.staffing.store', $shelter), ['email' => 'x@x.com'])->assertForbidden();
});

it('store validates that email is required', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), [])->assertSessionHasErrors('email');
});

it('returns error when email does not belong to any user', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => 'nobody@example.com'])
        ->assertSessionHasErrors('email');
});

it('owner cannot add themselves as worker', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => $owner->email])
        ->assertSessionHasErrors('email');
});

it('cannot add a Shelterowner type user as worker', function () {
    $owner       = User::factory()->shelterOwner()->create();
    $shelter     = Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherOwner  = User::factory()->shelterOwner()->create();
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => $otherOwner->email])
        ->assertSessionHasErrors('email');
});

it('returns error when user is already a worker at this shelter', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => $worker->email])
        ->assertSessionHasErrors('email');
});

it('assigns a User type user as Shelterworker, sets shelter_id, sends notification', function () {
    Notification::fake();

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $user    = User::factory()->create(['type' => 'User']);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => $user->email])
        ->assertRedirect();

    $user->refresh();
    expect($user->type)->toBe('Shelterworker')
        ->and($user->shelter_id)->toBe($shelter->id);

    Notification::assertSentTo($user, WorkerAssigned::class,
        fn ($n) => $n->shelter->is($shelter)
    );
});

it('assigns an unattached Shelterworker to the shelter', function () {
    Notification::fake();

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => null]);
    actingAs($owner);

    post(route('shelter.staffing.store', $shelter), ['email' => $worker->email])
        ->assertRedirect();

    $worker->refresh();
    expect($worker->shelter_id)->toBe($shelter->id)
        ->and($worker->type)->toBe('Shelterworker');
});

// ─── destroy ──────────────────────────────────────────────────────────────────

it('guest cannot remove worker', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);

    delete(route('shelter.staffing.destroy', [$shelter, $worker]))->assertRedirect(route('login'));
});

it('non-owner cannot remove worker', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    $other   = User::factory()->create();
    actingAs($other);

    delete(route('shelter.staffing.destroy', [$shelter, $worker]))->assertForbidden();
});

it('owner cannot remove worker who belongs to a different shelter', function () {
    $owner        = User::factory()->shelterOwner()->create();
    $shelter      = Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherShelter = Shelter::factory()->create();
    $worker       = User::factory()->shelterWorker()->create(['shelter_id' => $otherShelter->id]);
    actingAs($owner);

    delete(route('shelter.staffing.destroy', [$shelter, $worker]))->assertForbidden();
});

it('owner removes worker: shelter_id becomes null, type stays Shelterworker', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    delete(route('shelter.staffing.destroy', [$shelter, $worker]))->assertRedirect();

    $worker->refresh();
    expect($worker->shelter_id)->toBeNull()
        ->and($worker->type)->toBe('Shelterworker');
});

// ─── leave ────────────────────────────────────────────────────────────────────

it('guest cannot use leave route', function () {
    delete(route('staffing.leave'))->assertRedirect(route('login'));
});

it('user with non-worker type gets 403 on leave route', function () {
    $user = User::factory()->create(['type' => 'User']);
    actingAs($user);

    delete(route('staffing.leave'))->assertForbidden();
});

it('shelterowner type gets 403 on leave route', function () {
    $owner = User::factory()->shelterOwner()->create();
    actingAs($owner);

    delete(route('staffing.leave'))->assertForbidden();
});

it('worker without shelter gets error flash on leave', function () {
    $worker = User::factory()->shelterWorker()->create(['shelter_id' => null]);
    actingAs($worker);

    delete(route('staffing.leave'))
        ->assertRedirect()
        ->assertSessionHas('flash', fn ($f) => $f['type'] === 'error');
});

it('worker leaves shelter: shelter_id becomes null, type stays Shelterworker, redirects to dashboard', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($worker);

    delete(route('staffing.leave'))->assertRedirect(route('dashboard'));

    $worker->refresh();
    expect($worker->shelter_id)->toBeNull()
        ->and($worker->type)->toBe('Shelterworker');
});
