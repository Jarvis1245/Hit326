<?php
declare(strict_types=1);

// Prevent direct access to lib/views from leaking PHP source on misconfigured hosts
if (!defined('APP_ROOT')) define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/lib/config.php';
require_once APP_ROOT . '/lib/db.php';
require_once APP_ROOT . '/lib/helpers.php';
require_once APP_ROOT . '/lib/router.php';
require_once APP_ROOT . '/lib/controllers.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

// Strip the base path prefix so routes work in a sub-directory
$base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . ltrim($uri, '/');

register_routes();
dispatch($method, $uri);
