<?php

use function Pest\Laravel\get;

/**
 * Behind a TLS-terminating platform proxy the scheme only arrives in
 * X-Forwarded-Proto. If it is ignored, every generated URL downgrades to
 * http:// on an https site, which blocks assets and breaks post-login
 * redirects. These assertions pin the trustProxies configuration.
 */
it('treats a proxied request as secure', function () {
    get('/', ['X-Forwarded-Proto' => 'https'])->assertOk();

    expect(request()->isSecure())->toBeTrue();
});

it('generates https urls for a proxied request', function () {
    config(['app.url' => 'https://tappmancs-szakdolgozat.hu']);

    get('/', ['X-Forwarded-Proto' => 'https']);

    expect(url('/pets'))->toStartWith('https://');
    expect(route('about'))->toStartWith('https://');
});

it('honours the forwarded host', function () {
    get('/', [
        'X-Forwarded-Proto' => 'https',
        'X-Forwarded-Host'  => 'tappmancs-szakdolgozat.hu',
    ]);

    expect(request()->getHost())->toBe('tappmancs-szakdolgozat.hu');
    expect(url('/pets'))->toBe('https://tappmancs-szakdolgozat.hu/pets');
});

it('still works for a plain http request', function () {
    get('/')->assertOk();

    expect(request()->isSecure())->toBeFalse();
});
