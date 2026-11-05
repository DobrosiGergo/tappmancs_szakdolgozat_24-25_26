<?php

use function Pest\Laravel\get;

it('does not treat a spoofed X-Forwarded-Proto as secure', function () {
    get('/', ['X-Forwarded-Proto' => 'https'])->assertOk();

    expect(request()->isSecure())->toBeFalse();
});

it('does not adopt a spoofed X-Forwarded-Host when building urls', function () {
    get('/', ['X-Forwarded-Host' => 'attacker.example']);

    expect(request()->getHost())->not->toBe('attacker.example');
    expect(url('/pets'))->not->toContain('attacker.example');
});
