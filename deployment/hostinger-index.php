<?php

/**
 * ═══════════════════════════════════════════════════════════════════════
 * HOSTINGER PUBLIC ENTRYPOINT
 *
 * Replaces the default `public/index.php` when the Laravel app lives ONE
 * directory ABOVE public_html — i.e., Hostinger's recommended layout:
 *
 *   /home/USER/domains/rowaad.org/
 *     ├── public_html/          ← this file goes here as index.php
 *     └── laravel/              ← the whole Laravel repo lives here
 *
 * Copy this file into `public_html/index.php` after uploading the app.
 * ═══════════════════════════════════════════════════════════════════════
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ─── Adjust this if your Laravel folder has a different name ───
$laravelPath = __DIR__ . '/../laravel';

if (file_exists($laravelPath . '/storage/framework/maintenance.php')) {
    require $laravelPath . '/storage/framework/maintenance.php';
}

require $laravelPath . '/vendor/autoload.php';

$app = require_once $laravelPath . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
