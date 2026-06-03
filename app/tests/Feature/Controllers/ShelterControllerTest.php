<?php

use App\Models\Shelter;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('requires auth for shelter routes', function () {
get(route('shelter.setup'))->assertRedirect(route('login'));
    get(route('shelter.create'))->assertRedirect(route('login'));
    post(route('shelter.store'), [])->assertRedirect(route('login'));
    });

it('shows setup view for authenticated user', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('shelter.setup'))->assertOk();
});

it('index lists shelters (public route)', function () {
    $s1 = Shelter::factory()->create(['name' => 'Shelter Alpha']);
    $s2 = Shelter::factory()->create(['name' => 'Shelter Beta']);

    $resp = get(route('shelters.index'))
        ->assertOk()
        ->assertViewIs('shelters.index')
        ->assertViewHas('shelters', fn ($s) => $s->contains($s1) && $s->contains($s2));

    $resp->assertSeeText('Shelter Alpha')->assertSeeText('Shelter Beta');
});

it('show displays a shelter', function () {
    $shelter = Shelter::factory()->create();

    $resp = get(route('shelters.show', $shelter));
    $resp->assertOk();
    $resp->assertViewIs('shelters.show');
    $resp->assertViewHas('shelter', fn ($model) => $model->is($shelter));
});

it('store validates required fields', function () {
    $user = User::factory()->create();
    actingAs($user);

    post(route('shelter.store'), [])->assertSessionHasErrors(['name', 'description']);
});

it('store creates shelter and assigns owner, then redirects', function () {
    $user = User::factory()->create();
    actingAs($user);

    $resp = post(route('shelter.store'), [
        'name'        => 'Teszt Menhely',
        'description' => 'Leírás',
    ]);

    $resp->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('shelter', [
        'name'     => 'Teszt Menhely',
        'owner_id' => $user->id,
    ]);
});

it('store moves temp images from session into public storage and saves paths', function () {
    $user = User::factory()->create();
    actingAs($user);

    Storage::fake('public');

    Storage::disk('public')->put('tmp/a.jpg', 'x');
    Storage::disk('public')->put('tmp/b.jpg', 'y');

    session(['shelter_temp_images' => ['tmp/a.jpg', 'tmp/b.jpg']]);

    $resp = post(route('shelter.store'), [
        'name'        => 'Képes Menhely',
        'description' => 'Két kép kerül át',
    ]);
    $resp->assertRedirect(route('dashboard'));

    $shelter = Shelter::where('name', 'Képes Menhely')->firstOrFail();

    $expectedA = "shelters/{$shelter->uuid}/a.jpg";
    $expectedB = "shelters/{$shelter->uuid}/b.jpg";

    expect($shelter->images)->toMatchArray([$expectedA, $expectedB]);

    expect(Storage::disk('public')->exists($expectedA))->toBeTrue();
    expect(Storage::disk('public')->exists($expectedB))->toBeTrue();

    expect(Storage::disk('public')->exists('tmp/a.jpg'))->toBeFalse();
    expect(Storage::disk('public')->exists('tmp/b.jpg'))->toBeFalse();

    expect(session()->has('shelter_temp_images'))->toBeFalse();
});

it('index filters shelters by search query', function () {
    $match   = Shelter::factory()->create(['name' => 'Budapest Menhely']);
    $noMatch = Shelter::factory()->create(['name' => 'Pécsi Menhely']);

    get(route('shelters.index', ['search' => 'Budapest']))
        ->assertViewHas('shelters', fn ($s) => $s->contains($match) && ! $s->contains($noMatch));
});

it('create returns the setup view for authenticated user', function () {
    actingAs(User::factory()->create());

    get(route('shelter.create'))->assertOk()->assertViewIs('auth.registration.shelter.setup');
});

it('edit returns 403 for non-owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs(User::factory()->create());

    get(route('shelter.edit', $shelter))->assertForbidden();
});

it('edit returns the edit view with mode=edit for the shelter owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    get(route('shelter.edit', $shelter))
        ->assertOk()
        ->assertViewIs('auth.registration.shelter.setup')
        ->assertViewHas('mode', 'edit');
});

it('update returns 403 for non-owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs(User::factory()->create());

    put(route('shelter.update', $shelter), [
        'name'        => 'Changed',
        'description' => 'Changed description',
    ])->assertForbidden();
});

it('update validates required fields', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    put(route('shelter.update', $shelter), [])->assertSessionHasErrors(['name', 'description']);
});

it('update saves new name and description and redirects with success flash', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    put(route('shelter.update', $shelter), [
        'name'        => 'Frissített Menhely',
        'description' => 'Frissített leírás.',
    ])->assertRedirect(route('shelters.show', $shelter))
        ->assertSessionHas('flash', fn ($f) => $f['type'] === 'success');

    expect($shelter->fresh()->name)->toBe('Frissített Menhely');
});

it('update removes specified images from the shelter', function () {
    Storage::fake('public');

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create([
        'owner_id' => $owner->id,
        'images'   => ['shelters/uuid/a.jpg', 'shelters/uuid/b.jpg'],
    ]);
    actingAs($owner);

    put(route('shelter.update', $shelter), [
        'name'          => $shelter->name,
        'description'   => $shelter->description,
        'remove_images' => ['shelters/uuid/a.jpg'],
    ])->assertRedirect();

    expect($shelter->fresh()->images_safe)->toBe(['shelters/uuid/b.jpg']);
});

it('update moves temp images to permanent storage during update', function () {
    Storage::fake('public');

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    Storage::disk('public')->put('tmp/new.jpg', 'content');
    session(['shelter_temp_images' => ['tmp/new.jpg']]);

    put(route('shelter.update', $shelter), [
        'name'        => $shelter->name,
        'description' => $shelter->description,
    ])->assertRedirect();

    $expectedPath = 'shelters/' . $shelter->uuid . '/new.jpg';
    Storage::disk('public')->assertExists($expectedPath);
    expect($shelter->fresh()->images_safe)->toContain($expectedPath);
});
