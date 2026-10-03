<?php

use function Pest\Laravel\getJson;

it('hides the cron endpoints when no secret is configured', function () {
    config(['services.cron.secret' => null]);

    getJson('/cron/prune-uploads')->assertNotFound();
});

it('rejects a cron call without the bearer token', function () {
    config(['services.cron.secret' => 'test-secret']);

    getJson('/cron/prune-uploads')->assertNotFound();
});

it('rejects a cron call with the wrong bearer token', function () {
    config(['services.cron.secret' => 'test-secret']);

    getJson('/cron/prune-uploads', ['Authorization' => 'Bearer nope'])->assertNotFound();
});

it('runs the prune task for a correctly authorised cron call', function () {
    config(['services.cron.secret' => 'test-secret']);

    getJson('/cron/prune-uploads', ['Authorization' => 'Bearer test-secret'])
        ->assertOk()
        ->assertJsonPath('task', 'prune-uploads');
});

it('404s an unknown cron task even when authorised', function () {
    config(['services.cron.secret' => 'test-secret']);

    getJson('/cron/nope', ['Authorization' => 'Bearer test-secret'])->assertNotFound();
});
