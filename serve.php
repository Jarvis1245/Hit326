<?php
// Router script for PHP's built-in dev server.
// Usage: php -S localhost:8080 serve.php
//
// Static files under public/ are served natively; everything else
// is dispatched through public/index.php.
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . '/public' . $path;
    if (is_file($file)) {
        return false; // Let PHP serve it directly (CSS, images, etc.)
    }
}
require __DIR__ . '/public/index.php';
