<?php

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

function petPayload(): array
{
    $species = Specie::factory()->create();
    $breed   = Breed::factory()->create(['species_id' => $species->id]);

    return [
        'name'         => 'Bodri',
        'species_id'   => $species->id,
        'breed_id'     => $breed->id,
        'birth_date'   => now()->subYears(2)->toDateString(),
        'arrival_date' => now()->subMonths(3)->toDateString(),
        'gender'       => 'male',
        'description'  => str_repeat('a', 30),
        'status'       => 'free',
    ];
}

// ─── index ────────────────────────────────────────────────────────────────────

it('index is publicly accessible', function () {
    get(route('pets.index'))->assertOk()->assertViewIs('pets.index');
});

it('index lists all pets', function () {
    $pet = Pet::factory()->create();
    get(route('pets.index'))->assertViewHas('pets', fn ($pets) => $pets->contains($pet));
});

it('index filters by name search', function () {
    $matching    = Pet::factory()->create(['name' => 'Bodri']);
    $nonMatching = Pet::factory()->create(['name' => 'Cica']);

    get(route('pets.index', ['search' => 'Bodri']))
        ->assertViewHas('pets', fn ($pets) => $pets->contains($matching) && ! $pets->contains($nonMatching));
});

it('index filters by species', function () {
    $species     = Specie::factory()->create();
    $matching    = Pet::factory()->create(['species_id' => $species->id]);
    $nonMatching = Pet::factory()->create();

    get(route('pets.index', ['species' => $species->id]))
        ->assertViewHas('pets', fn ($pets) => $pets->contains($matching) && ! $pets->contains($nonMatching));
});

it('index filters by gender', function () {
    $matching    = Pet::factory()->create(['gender' => 'male']);
    $nonMatching = Pet::factory()->create(['gender' => 'female']);

    get(route('pets.index', ['gender' => 'male']))
        ->assertViewHas('pets', fn ($pets) => $pets->contains($matching) && ! $pets->contains($nonMatching));
});

it('index filters by status', function () {
    $matching    = Pet::factory()->create(['status' => 'adopted']);
    $nonMatching = Pet::factory()->create(['status' => 'free']);

    get(route('pets.index', ['status' => 'adopted']))
        ->assertViewHas('pets', fn ($pets) => $pets->contains($matching) && ! $pets->contains($nonMatching));
});

// ─── show ─────────────────────────────────────────────────────────────────────

it('show is publicly accessible and returns the correct pet', function () {
    $pet = Pet::factory()->create();

    get(route('pets.show', $pet))
        ->assertOk()
        ->assertViewIs('pets.show')
        ->assertViewHas('pet', fn ($p) => $p->is($pet));
});

it('show passes related pets from the same shelter excluding the current pet', function () {
    $shelter   = Shelter::factory()->create();
    $pet       = Pet::factory()->create(['shelter_id' => $shelter->id]);
    $related   = Pet::factory()->create(['shelter_id' => $shelter->id]);
    $unrelated = Pet::factory()->create();

    get(route('pets.show', $pet))
        ->assertViewHas('relatedPets', fn ($rp) => $rp->contains($related) && ! $rp->contains($unrelated) && ! $rp->contains($pet));
});

// ─── create ───────────────────────────────────────────────────────────────────

it('create redirects guests to login', function () {
    get(route('pets.create'))->assertRedirect(route('login'));
});

it('create blocks regular User type with 403', function () {
    actingAs(User::factory()->create(['type' => 'User']));
    get(route('pets.create'))->assertForbidden();
});

it('create returns 404 when shelter owner has no shelter', function () {
    actingAs(User::factory()->shelterOwner()->create());
    get(route('pets.create'))->assertNotFound();
});

it('create is accessible for shelter owner with an existing shelter', function () {
    $owner = User::factory()->shelterOwner()->create();
    Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    get(route('pets.create'))->assertOk();
});

it('create is accessible for shelter worker assigned to a shelter', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($worker);

    get(route('pets.create'))->assertOk();
});

// ─── store ────────────────────────────────────────────────────────────────────

it('store redirects guests to login', function () {
    post(route('pets.store'), [])->assertRedirect(route('login'));
});

it('store blocks regular User type with 403', function () {
    actingAs(User::factory()->create(['type' => 'User']));
    post(route('pets.store'), [])->assertForbidden();
});

it('store validates required fields', function () {
    $owner = User::factory()->shelterOwner()->create();
    Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    post(route('pets.store'), [])->assertSessionHasErrors(['name', 'species_id', 'breed_id']);
});

it('store creates pet for shelter owner and redirects to pet show page', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    post(route('pets.store'), petPayload());

    $pet = Pet::where('name', 'Bodri')->first();
    expect($pet)->not->toBeNull()
        ->and($pet->shelter_id)->toBe($shelter->id)
        ->and($pet->employee_id)->toBe($owner->id);
});

it('store creates pet for shelter worker and assigns correct shelter', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    actingAs($worker);

    post(route('pets.store'), petPayload());

    $pet = Pet::where('name', 'Bodri')->first();
    expect($pet)->not->toBeNull()->and($pet->shelter_id)->toBe($shelter->id);
});

it('store moves temp images from session to permanent storage', function () {
    Storage::fake('public');

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    actingAs($owner);

    Storage::disk('public')->put('tmp/dog.jpg', 'content');
    session(['pet_temp_images' => ['tmp/dog.jpg']]);

    post(route('pets.store'), petPayload());

    $pet          = Pet::where('name', 'Bodri')->firstOrFail();
    $expectedPath = 'pets/' . $shelter->uuid . '/' . $pet->uuid . '/dog.jpg';

    expect($pet->images)->toMatchArray([$expectedPath]);
    Storage::disk('public')->assertExists($expectedPath);
    Storage::disk('public')->assertMissing('tmp/dog.jpg');
    expect(session()->has('pet_temp_images'))->toBeFalse();
});

// ─── update.index ─────────────────────────────────────────────────────────────

it('update index redirects guests to login', function () {
    get(route('pets.update.index'))->assertRedirect(route('login'));
});

it('update index blocks regular User type', function () {
    actingAs(User::factory()->create(['type' => 'User']));
    get(route('pets.update.index'))->assertForbidden();
});

it('update index shows pets belonging to the shelter owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    get(route('pets.update.index'))->assertViewHas('pets', fn ($pets) => $pets->contains($pet));
});

it('update index shows pets assigned to shelter worker', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id, 'employee_id' => $worker->id]);
    actingAs($worker);

    get(route('pets.update.index'))->assertViewHas('pets', fn ($pets) => $pets->contains($pet));
});

// ─── edit ─────────────────────────────────────────────────────────────────────

it('edit redirects guests to login', function () {
    $pet = Pet::factory()->create();
    get(route('pets.edit', $pet))->assertRedirect(route('login'));
});

it('edit returns 403 for worker not assigned to that shelter', function () {
    $other   = User::factory()->shelterWorker()->create();
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($other);

    get(route('pets.edit', $pet))->assertForbidden();
});

it('edit is accessible for the shelter owner', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    get(route('pets.edit', $pet))->assertOk()->assertViewIs('pets.update.edit');
});

it('edit is accessible for a worker assigned to the pets shelter', function () {
    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($worker);

    get(route('pets.edit', $pet))->assertOk();
});

// ─── update ───────────────────────────────────────────────────────────────────

it('update redirects guests to login', function () {
    $pet = Pet::factory()->create();
    put(route('pets.update', $pet), [])->assertRedirect(route('login'));
});

it('update returns 403 for worker not assigned to that shelter', function () {
    $other   = User::factory()->shelterWorker()->create();
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($other);

    put(route('pets.update', $pet), petPayload())->assertForbidden();
});

it('owner can update pet name and data', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    $payload         = petPayload();
    $payload['name'] = 'Fido Updated';

    put(route('pets.update', $pet), $payload)->assertRedirect(route('pets.update.index'));

    expect($pet->fresh()->name)->toBe('Fido Updated');
});

it('update redirects to update index with success flash', function () {
    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    put(route('pets.update', $pet), petPayload())
        ->assertRedirect(route('pets.update.index'))
        ->assertSessionHas('flash', fn ($f) => $f['type'] === 'success');
});

// ─── destroy ──────────────────────────────────────────────────────────────────

it('destroy redirects guests to login', function () {
    $pet = Pet::factory()->create();
    delete(route('pets.destroy', $pet))->assertRedirect(route('login'));
});

it('destroy returns 403 for worker not assigned to that shelter', function () {
    $other   = User::factory()->shelterWorker()->create();
    $shelter = Shelter::factory()->create();
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($other);

    delete(route('pets.destroy', $pet))->assertForbidden();
});

it('owner can delete pet and it is removed from database', function () {
    Storage::fake('public');

    $owner   = User::factory()->shelterOwner()->create();
    $shelter = Shelter::factory()->create(['owner_id' => $owner->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($owner);

    delete(route('pets.destroy', $pet))
        ->assertRedirect(route('pets.update.index'))
        ->assertSessionHas('flash', fn ($f) => $f['type'] === 'success');

    expect(Pet::find($pet->id))->toBeNull();
});

it('worker assigned to shelter can delete its pet', function () {
    Storage::fake('public');

    $shelter = Shelter::factory()->create();
    $worker  = User::factory()->shelterWorker()->create(['shelter_id' => $shelter->id]);
    $pet     = Pet::factory()->create(['shelter_id' => $shelter->id]);
    actingAs($worker);

    delete(route('pets.destroy', $pet))->assertRedirect(route('pets.update.index'));

    expect(Pet::find($pet->id))->toBeNull();
});
