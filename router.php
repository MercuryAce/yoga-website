<?php

$uri = urldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
if ($uri === '') {
    $uri = '/';
}

$blocked = str_contains($uri, '..')
    || (bool) preg_match('#/\.#', $uri)
    || (bool) preg_match('#^/(data|includes)/#', $uri)
    || (bool) preg_match('#^/images/recipes/.*\.(php|phtml|phar)$#i', $uri);

if ($blocked) {
    require_once __DIR__ . '/includes/security.php';
    cs_send_security_headers();
    http_response_code(403);
    exit('Forbidden');
}

if ($uri === '/sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

$legacy = [
    '/index.html' => '/index.php',
    '/yoga.html' => '/yoga.php',
    '/creative.html' => '/creative.php',
    '/reviews.html' => '/reviews.php',
    '/vegan.html' => '/vegan.php',
];
if (isset($legacy[$uri])) {
    require_once __DIR__ . '/includes/security.php';
    header('Location: ' . $legacy[$uri], true, 301);
    exit;
}

$root = realpath(__DIR__);
if ($root === false) {
    return false;
}

if ($uri !== '/') {
    $file = realpath(__DIR__ . $uri);
    if ($file !== false && is_file($file)) {
        if (!str_starts_with($file, $root . DIRECTORY_SEPARATOR) && $file !== $root) {
            require_once __DIR__ . '/includes/security.php';
            cs_send_security_headers();
            http_response_code(403);
            exit('Forbidden');
        }
    }
}

return false;
