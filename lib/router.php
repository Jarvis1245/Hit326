<?php

// Global route table: [ [method, pattern, handler], ... ]
$_routes = [];

function add_route(string $method, string $pattern, callable $handler): void
{
    global $_routes;
    $_routes[] = [strtoupper($method), $pattern, $handler];
}

// Dispatch the incoming request against registered routes.
// :id  matches digits only  → ([0-9]+)
// :foo matches any non-slash segment → ([^/]+)
function dispatch(string $method, string $uri): void
{
    global $_routes;

    // Strip query string if present
    $uri = strtok($uri, '?') ?: '/';
    $uri = '/' . trim($uri, '/');
    if ($uri === '//') $uri = '/';

    $method = strtoupper($method);

    foreach ($_routes as [$rMethod, $pattern, $handler]) {
        if ($method !== $rMethod) continue;

        // Build regex: :id → digits-only capture; :name → non-slash capture
        $regex = preg_replace('/:id\b/', '([0-9]+)', $pattern);
        $regex = preg_replace('/:([a-z_]+)/', '([^/]+)', $regex);
        $regex = '#^' . $regex . '$#';

        // Collect param names from the original pattern
        preg_match_all('/:([a-z_]+)/', $pattern, $names);

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches); // drop full-match entry
            $params = !empty($names[1]) ? array_combine($names[1], $matches) : [];
            $handler($params);
            return;
        }
    }

    http_response_code(404);
    render('404', []);
}
