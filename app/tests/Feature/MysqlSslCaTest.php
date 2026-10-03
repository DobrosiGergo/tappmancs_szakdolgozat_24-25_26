<?php

use Pdo\Mysql;

/**
 * The managed MySQL provider requires TLS, so the connection needs a CA cert.
 * config/database.php resolves it from database/certs/ca.pem rather than from
 * an absolute path in an env var, because the serverless function's working
 * directory is not knowable up front.
 *
 * database_path() is deliberate: api/index.php redirects storage_path() to
 * /tmp for the read-only filesystem, so a cert under storage/ would not be
 * found at runtime.
 *
 * These tests never write to or delete the real database/certs/ca.pem. The
 * absent-cert case relocates the database path instead, so a developer's
 * actual certificate is never at risk.
 */
function sslCaOption(): mixed
{
    $key    = class_exists(Mysql::class) ? Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA;
    $config = require config_path('database.php');

    return $config['connections']['mysql']['options'][$key] ?? null;
}

it('resolves the bundled ca.pem when it is present', function () {
    $path = database_path('certs/ca.pem');

    if (! file_exists($path)) {
        $this->markTestSkipped('No CA certificate bundled; nothing to resolve.');
    }

    expect(sslCaOption())->toBe($path);
});

it('omits the ssl option entirely when no cert is bundled', function () {
    // Relocate the database path rather than deleting the real certificate.
    $empty = sys_get_temp_dir() . '/tappmancs-no-cert-' . uniqid();
    mkdir($empty, 0755, true);
    app()->useDatabasePath($empty);

    try {
        expect(sslCaOption())->toBeNull();
    } finally {
        rmdir($empty);
    }
});

it('lets an env override win over the bundled cert', function () {
    putenv('MYSQL_ATTR_SSL_CA=/custom/elsewhere.pem');

    try {
        expect(sslCaOption())->toBe('/custom/elsewhere.pem');
    } finally {
        putenv('MYSQL_ATTR_SSL_CA');
    }
});

it('resolves the cert outside the relocated storage path', function () {
    // storage_path() moves to /tmp under serverless; database_path() must not.
    expect(database_path('certs'))->not->toStartWith('/tmp');
});
