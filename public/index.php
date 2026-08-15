<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$envPath = __DIR__.'/../.env';
$appDebug = false;
if (is_file($envPath) && preg_match('/^APP_DEBUG\s*=\s*(true|1)/mi', (string) file_get_contents($envPath))) {
    $appDebug = true;
}

if ($appDebug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

$autoload = __DIR__.'/../vendor/autoload.php';
if (! is_file($autoload)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "SCiREG error: vendor/autoload.php not found.\n";
    echo "Run in /www/scireg:\n";
    echo "  composer install --no-dev --optimize-autoloader\n";
    exit(1);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $autoload;

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
