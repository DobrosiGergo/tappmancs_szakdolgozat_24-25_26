<?php

use App\Models\Shelter;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

it('guards settings routes for guests', function () {
get(route('settings.index'))->assertRedirect(route('login'));
    get(route('settings.profile'))->assertRedirect(route('login'));
    get(route('settings.password'))->assertRedirect(route('login'));
    get(route('settings.delete'))->assertRedirect(route('login'));
    delete(route('settings.delete.confirm'))->assertRedirect(route('login'));
    });

it('shows settings pages for authenticated user', function () {
    $user = User::factory()->create();
    actingAs($user);

    get(route('settings.index'))->assertOk()->assertViewIs('auth.settings.index');
    get(route('settings.profile'))->assertOk()->assertViewIs('auth.settings.profile');
    get(route('settings.password'))->assertOk()->assertViewIs('auth.settings.password');
    get(route('settings.delete'))->assertOk()->assertViewIs('auth.settings.delete');
});

it('updates profile without changing email keeps verification date', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'name'              => 'Old Name',
    ]);
    actingAs($user);

    $resp = put(route('settings.profile.update'), [
        'name'  => 'New Name',
        'email' => $user->email,
    ]);

    $resp->assertRedirect(route('settings.profile'));
    $resp->assertSessionHas('status', 'profile-updated');

    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email_verified_at)->not->toBeNull();
});

it('updates profile and resets email_verified_at when email changes', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'email'             => 'old@example.com',
    ]);
    actingAs($user);

    $resp = put(route('settings.profile.update'), [
        'name'  => $user->name,
        'email' => 'new@example.com',
    ]);

    $resp->assertRedirect(route('settings.profile'));
    $user->refresh();
    expect($user->email)->toBe('new@example.com');
    expect($user->email_verified_at)->toBeNull();
});

it('requires current password to delete account', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret123'),
    ]);
    actingAs($user);

    $resp = delete(route('settings.delete.confirm'), [
        'password' => 'wrong',
    ]);
    $resp->assertSessionHasErrors('password', null, 'userDeletion');
});

it('deletes shelterowner account, removes shelter images & folder, logs out and redirects home', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'type'     => 'Shelterowner',
        'password' => Hash::make('secret123'),
    ]);
    actingAs($user);

    $shelter = Shelter::factory()->create([
        'owner_id' => $user->id,
        'images'   => [],
    ]);

    $finalA = "shelters/{$shelter->id}/a.jpg";
    $finalB = "shelters/{$shelter->id}/b.jpg";
    Storage::disk('public')->put($finalA, 'x');
    Storage::disk('public')->put($finalB, 'y');
    $shelter->images = [$finalA, $finalB];
    $shelter->save();

    $resp = delete(route('settings.delete.confirm'), [
        'password' => 'secret123',
    ]);

    $resp->assertRedirect('/');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);

    Storage::disk('public')->assertMissing($finalA);
    Storage::disk('public')->assertMissing($finalB);
    Storage::disk('public')->assertMissing("shelters/{$shelter->id}");

    get(route('settings.index'))->assertRedirect(route('login'));
});
