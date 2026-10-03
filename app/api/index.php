<?php

/**
 * Vercel serverless entrypoint.
 *
 * A Vercel function's filesystem is read-only apart from /tmp, which Laravel
 * cannot cope with out of the box: it writes compiled views, logs and caches
 * under storage/. LARAVEL_STORAGE_PATH is read by Illuminate\Foundation\
 * Application::storagePath(), so pointing it at /tmp relocates all of them in
 * one move, before the framework boots.
 *
 * /tmp survives for the life of a warm instance and is discarded on cold
 * start, so nothing durable may live here — uploads go to UPLOADS_DISK,
 * sessions and cache to Postgres.
 */
$storage = '/tmp/storage';

foreach ([
    '/framework/views',
    '/framework/cache/data',
    '/framework/sessions',
    '/framework/testing',
    '/logs',
    '/app/public',
] as $dir) {
    if (! is_dir($storage . $dir)) {
        mkdir($storage . $dir, 0755, true);
    }
}

$_ENV['LARAVEL_STORAGE_PATH'] = $_SERVER['LARAVEL_STORAGE_PATH'] = $storage;
putenv('LARAVEL_STORAGE_PATH=' . $storage);

require __DIR__ . '/../public/index.php';
