<?php

use App\Models\Form;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

// ─── store ────────────────────────────────────────────────────────────────────

it('store redirects guests to login', function () {
    post(route('messages.store'), [])->assertRedirect(route('login'));
});

it('store blocks shelter owners with 403 (requires User role)', function () {
    actingAs(User::factory()->shelterOwner()->create());
    post(route('messages.store'), [])->assertForbidden();
});

it('store blocks shelter workers with 403 (requires User role)', function () {
    actingAs(User::factory()->shelterWorker()->create());
    post(route('messages.store'), [])->assertForbidden();
});

it('store validates shelter_id and message are required', function () {
    actingAs(User::factory()->create(['type' => 'User']));
    post(route('messages.store'), [])->assertSessionHasErrors(['shelter_id', 'message']);
});

it('store rejects message shorter than 10 characters', function () {
    $shelter = Shelter::factory()->create();
    actingAs(User::factory()->create(['type' => 'User']));

    post(route('messages.store'), [
        'shelter_id' => $shelter->id,
        'message'    => 'Short',
    ])->assertSessionHasErrors('message');
});

it('store rejects message longer than 2000 characters', function () {
    $shelter = Shelter::factory()->create();
    actingAs(User::factory()->create(['type' => 'User']));

    post(route('messages.store'), [
        'shelter_id' => $shelter->id,
        'message'    => str_repeat('a', 2001),
    ])->assertSessionHasErrors('message');
});

it('store rejects non-existent shelter_id', function () {
    actingAs(User::factory()->create(['type' => 'User']));

    post(route('messages.store'), [
        'shelter_id' => 99999,
        'message'    => 'Érdeklődöm a menhelyről.',
    ])->assertSessionHasErrors('shelter_id');
});

it('store creates message with pet and redirects back with success flash', function () {
    $user    = User::factory()->create(['type' => 'User']);
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($user);

    post(route('messages.store'), [
        'shelter_id' => $shelter->id,
        'pet_id'     => $pet->id,
        'message'    => 'Érdeklődöm a kisállat iránt, örökbe szeretném fogadni.',
    ])->assertRedirect()->assertSessionHas('flash', fn ($f) => $f['type'] === 'success');

    $this->assertDatabaseHas('form_messages', [
        'user_id'    => $user->id,
        'shelter_id' => $shelter->id,
        'pet_id'     => $pet->id,
    ]);
});

it('store prevents duplicate message for the same pet', function () {
    $user    = User::factory()->create(['type' => 'User']);
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($user);

    Form::factory()->create([
        'user_id'    => $user->id,
        'shelter_id' => $shelter->id,
        'pet_id'     => $pet->id,
    ]);

    post(route('messages.store'), [
        'shelter_id' => $shelter->id,
        'pet_id'     => $pet->id,
        'message'    => 'Ismételt érdeklődés a kisállat iránt ebben az esetben.',
    ])->assertSessionHasErrors('message');
});

// ─── index ────────────────────────────────────────────────────────────────────

it('index redirects guests to login', function () {
    get(route('messages.index'))->assertRedirect(route('login'));
});

it('index blocks regular User type with 403', function () {
    actingAs(User::factory()->create(['type' => 'User']));
    get(route('messages.index'))->assertForbidden();
});

it('index redirects to shelter setup when owner has no shelter', function () {
    actingAs(User::factory()->shelterOwner()->create());
    get(route('messages.index'))->assertRedirect(route('shelter.setup'));
});

it('index shows messages for shelter owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $message = Form::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    get(route('messages.index'))
        ->assertOk()
        ->assertViewIs('messages.index')
        ->assertViewHas('messages', fn ($msgs) => $msgs->contains($message));
});

it('index only shows messages belonging to the owners shelter', function () {
    $owner        = User::factory()->shelterOwner()->create();
    $shelter      = Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherShelter = Shelter::factory()->create();
    $ownMessage   = Form::factory()->create(['shelter_id' => $shelter->id]);
    $otherMessage = Form::factory()->create(['shelter_id' => $otherShelter->id]);
    actingAs($owner);

    get(route('messages.index'))
        ->assertViewHas('messages', fn ($msgs) => $msgs->contains($ownMessage) && ! $msgs->contains($otherMessage));
});

// ─── show ─────────────────────────────────────────────────────────────────────

it('show redirects guests to login', function () {
    $form = Form::factory()->create();
    get(route('messages.show', $form))->assertRedirect(route('login'));
});

it('show returns 403 for message from different shelter', function () {
    $owner        = User::factory()->shelterOwner()->create();
    Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherShelter = Shelter::factory()->create();
    $form         = Form::factory()->create(['shelter_id' => $otherShelter->id]);
    actingAs($owner);

    get(route('messages.show', $form))->assertForbidden();
});

it('show displays the message for the correct shelter owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $form    = Form::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    get(route('messages.show', $form))
        ->assertOk()
        ->assertViewIs('messages.show')
        ->assertViewHas('form', fn ($f) => $f->is($form));
});

// ─── destroy ──────────────────────────────────────────────────────────────────

it('destroy redirects guests to login', function () {
    $form = Form::factory()->create();
    delete(route('messages.destroy', $form))->assertRedirect(route('login'));
});

it('destroy returns 403 for message from different shelter', function () {
    $owner        = User::factory()->shelterOwner()->create();
    Shelter::factory()->create(['owner_id' => $owner->id]);
    $otherShelter = Shelter::factory()->create();
    $form         = Form::factory()->create(['shelter_id' => $otherShelter->id]);
    actingAs($owner);

    delete(route('messages.destroy', $form))->assertForbidden();
});

it('destroy deletes the message and redirects to index with success flash', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $form    = Form::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    delete(route('messages.destroy', $form))
        ->assertRedirect(route('messages.index'))
        ->assertSessionHas('flash', fn ($f) => $f['type'] === 'success');

    $this->assertDatabaseMissing('form_messages', ['id' => $form->id]);
});
