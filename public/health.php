<?php

/**
 * Lightweight probe that does not boot Laravel.
 * Visit: https://scireg.sc.kku.ac.th/health.php
 */
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$root = dirname(__DIR__);
$checks = [
    'php_version' => PHP_VERSION,
    'php_sapi' => PHP_SAPI,
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
    'app_root' => $root,
    'env_exists' => is_file($root.'/.env') ? 'yes' : 'no',
    'vendor_autoload' => is_file($root.'/vendor/autoload.php') ? 'yes' : 'no',
    'storage_writable' => is_writable($root.'/storage') ? 'yes' : 'no',
    'bootstrap_cache_writable' => is_writable($root.'/bootstrap/cache') ? 'yes' : 'no',
    'laravel_log' => is_file($root.'/storage/logs/laravel.log') ? 'yes' : 'no',
];

echo "SCiREG health\n";
echo "=============\n";
foreach ($checks as $key => $value) {
    echo $key.': '.$value."\n";
}

if (is_file($root.'/.env')) {
    $env = (string) file_get_contents($root.'/.env');
    preg_match('/^APP_ENV\s*=\s*(.+)$/m', $env, $envMatch);
    preg_match('/^APP_DEBUG\s*=\s*(.+)$/m', $env, $debugMatch);
    preg_match('/^APP_URL\s*=\s*(.+)$/m', $env, $urlMatch);
    echo 'APP_ENV: '.trim($envMatch[1] ?? '(missing)')."\n";
    echo 'APP_DEBUG: '.trim($debugMatch[1] ?? '(missing)')."\n";
    echo 'APP_URL: '.trim($urlMatch[1] ?? '(missing)')."\n";
}

echo "\nOK\n";
