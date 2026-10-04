<?php

use function Pest\Laravel\get;

/**
 * The application is served by nginx on its own host, so the origin is
 * reachable directly and X-Forwarded-* headers can be set by any client.
 * Trusting them here would let a caller control the scheme and host used to
 * build URLs, which is a cache-poisoning and phishing vector.
 *
 * nginx supplies the real scheme through the HTTPS fastcgi parameter, so no
 * proxy trust is needed. These assertions exist to stop trustProxies from
 * being reintroduced without a specific proxy address: the serverless
 * deployment needed a wildcard, and that wildcard is wrong on a VM.
 */
it('does not treat a spoofed X-Forwarded-Proto as secure', function () {
    get('/', ['X-Forwarded-Proto' => 'https'])->assertOk();

    expect(request()->isSecure())->toBeFalse();
});

it('does not adopt a spoofed X-Forwarded-Host when building urls', function () {
    get('/', ['X-Forwarded-Host' => 'attacker.example']);

    expect(request()->getHost())->not->toBe('attacker.example');
    expect(url('/pets'))->not->toContain('attacker.example');
});
