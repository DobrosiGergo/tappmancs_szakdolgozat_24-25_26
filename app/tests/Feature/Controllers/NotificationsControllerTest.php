<?php

use App\Models\Shelter;
use App\Models\User;
use App\Notifications\WorkerAssigned;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('guests are redirected to login', function () {
    get(route('notifications.index'))->assertRedirect(route('login'));
});

it('shows notifications page for authenticated user', function () {
    actingAs(User::factory()->create());

    get(route('notifications.index'))
        ->assertOk()
        ->assertViewIs('notifications.index')
        ->assertViewHas('notifications');
});

it('notifications collection is empty when user has no notifications', function () {
    actingAs(User::factory()->create());

    get(route('notifications.index'))
        ->assertViewHas('notifications', fn ($n) => $n->isEmpty());
});

it('marks all unread notifications as read on visit', function () {
    $user    = User::factory()->create();
    $shelter = Shelter::factory()->create();
    actingAs($user);

    $user->notify(new WorkerAssigned($shelter));

    expect($user->unreadNotifications()->count())->toBe(1);

    get(route('notifications.index'));

    expect($user->fresh()->unreadNotifications()->count())->toBe(0);
});

it('notifications are passed to the view in descending order', function () {
    $user    = User::factory()->create();
    $shelter = Shelter::factory()->create();
    actingAs($user);

    $user->notify(new WorkerAssigned($shelter));
    $user->notify(new WorkerAssigned($shelter));

    get(route('notifications.index'))
        ->assertViewHas('notifications', fn ($n) => $n->count() === 2);
});
