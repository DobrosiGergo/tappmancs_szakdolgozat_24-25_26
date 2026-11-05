<?php

// Laravel 11 uses PDO::MYSQL_ATTR_SSL_CA, deprecated in PHP 8.5. Composer runs
// this after every install, because each install restores the vendor files.

if (PHP_VERSION_ID < 80500) {
    exit(0);
}

$root = dirname(__DIR__);

$files = [
    $root . '/vendor/laravel/framework/config/database.php',
    $root . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlSchemaState.php',
];

foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }

    $content = file_get_contents($file);
    $fixed   = preg_replace('/\\\\?PDO::MYSQL_ATTR_SSL_CA/', '\\Pdo\\Mysql::ATTR_SSL_CA', $content);

    if ($fixed !== $content) {
        file_put_contents($file, $fixed);
        echo 'Patched: ' . basename($file) . PHP_EOL;
    }
}
