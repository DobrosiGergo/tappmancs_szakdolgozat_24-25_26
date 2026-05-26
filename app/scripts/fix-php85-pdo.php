<?php

/**
 * Patches Laravel framework vendor files for PHP 8.5 compatibility.
 * PDO::MYSQL_ATTR_SSL_CA was deprecated in PHP 8.5; the replacement
 * is Pdo\Mysql::ATTR_SSL_CA, available since PHP 8.4.
 *
 * Run automatically via composer post-install-cmd / post-update-cmd.
 */

if (PHP_VERSION_ID < 80500) {
    exit(0);
}

$root = dirname(__DIR__);

$replacements = [
    $root . '/vendor/laravel/framework/config/database.php' => [
        'PDO::MYSQL_ATTR_SSL_CA' =>
            '(PHP_VERSION_ID >= 80500 ? \\Pdo\\Mysql::ATTR_SSL_CA : PDO::MYSQL_ATTR_SSL_CA)',
    ],
    $root . '/vendor/laravel/framework/src/Illuminate/Database/Schema/MySqlSchemaState.php' => [
        '\PDO::MYSQL_ATTR_SSL_CA' =>
            '(PHP_VERSION_ID >= 80500 ? \\Pdo\\Mysql::ATTR_SSL_CA : \\PDO::MYSQL_ATTR_SSL_CA)',
    ],
];

foreach ($replacements as $file => $pairs) {
    if (! file_exists($file)) {
        continue;
    }

    $content = file_get_contents($file);
    $fixed   = str_replace(array_keys($pairs), array_values($pairs), $content);

    if ($fixed !== $content) {
        file_put_contents($file, $fixed);
        echo "Patched: " . basename(dirname($file)) . '/' . basename($file) . PHP_EOL;
    }
}
