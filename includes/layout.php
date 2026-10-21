<?php

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string) $_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    http_response_code(403);
    exit;
}

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/store.php';
require_once __DIR__ . '/seo.php';

function cs_page(array $page): array
{
    $path = (string) ($page['path'] ?? 'index.php');
    $title = (string) ($page['title'] ?? 'Creative Space');
    $description = (string) ($page['description'] ?? '');
    return array_merge([
        'path' => $path,
        'title' => $title,
        'description' => $description,
        'twitter_description' => $description,
        'header_class' => '',
        'main_class' => '',
        'about_href' => $path === 'index.php' ? '#about' : '/index.php#about',
        'credit' => 'standard',
        'schema_extra' => [],
    ], $page);
}

function cs_layout_start(array $page): void
{
    cs_send_security_headers();
    $GLOBALS['cs_page'] = cs_page($page);
    require __DIR__ . '/head.php';
    require __DIR__ . '/header.php';
}

function cs_layout_end(): void
{
    require __DIR__ . '/contact.php';
    require __DIR__ . '/footer.php';
}
