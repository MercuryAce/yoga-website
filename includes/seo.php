<?php

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === basename(__FILE__)) {
    http_response_code(403);
    exit;
}

require_once dirname(__DIR__) . '/data/config.php';

function cs_site_origin(): string
{
    if (defined('CS_SITE_ORIGIN') && CS_SITE_ORIGIN !== '') {
        return rtrim((string) CS_SITE_ORIGIN, '/');
    }
    $forwarded = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    $https = $forwarded === 'https'
        || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    $host = (string) ($_SERVER['HTTP_HOST'] ?? '127.0.0.1');
    return ($https ? 'https' : 'http') . '://' . $host;
}

function cs_business_schema(string $origin): array
{
    return [
        '@type' => ['LocalBusiness', 'SportsActivityLocation'],
        '@id' => $origin . '/#business',
        'name' => 'Creative Space',
        'alternateName' => 'Creative Collective',
        'description' => 'Private and group yoga classes, creative collaborations, and a vegan kitchen with Vinay Soraya in Shoreditch, London.',
        'url' => $origin . '/index.php',
        'image' => $origin . '/images/og-image.jpg',
        'logo' => $origin . '/images/logo.png',
        'email' => 'mycreativesspace.yoga@gmail.com',
        'telephone' => '+447399968655',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '20 Kingsland Road',
            'addressLocality' => 'London',
            'addressRegion' => 'England',
            'postalCode' => 'E2 8DA',
            'addressCountry' => 'GB',
        ],
        'founder' => [
            '@type' => 'Person',
            'name' => 'Vinay Soraya',
            'gender' => 'Female',
        ],
        'areaServed' => ['Shoreditch', 'Hoxton', 'East London'],
    ];
}

function cs_recipe_schema(array $recipes, string $origin): array
{
    $out = [];
    foreach ($recipes as $recipe) {
        $title = (string) ($recipe['title'] ?? '');
        if ($title === '') {
            continue;
        }
        $image = (string) ($recipe['image'] ?? '');
        $entry = [
            '@type' => 'Recipe',
            'name' => $title,
            'author' => [
                '@type' => 'Person',
                'name' => 'Vinay Soraya',
                'gender' => 'Female',
            ],
            'recipeCategory' => 'Vegan',
            'recipeCuisine' => 'Plant-based',
            'recipeIngredient' => array_values($recipe['ingredients'] ?? []),
        ];
        if ($image !== '') {
            $entry['image'] = $origin . '/' . ltrim($image, '/');
        }
        $steps = [];
        foreach ($recipe['method'] ?? [] as $step) {
            $steps[] = [
                '@type' => 'HowToStep',
                'text' => $step,
            ];
        }
        if ($steps) {
            $entry['recipeInstructions'] = $steps;
        }
        $out[] = $entry;
    }
    return $out;
}

function cs_json_ld(string $path, string $title, string $description, array $extra = []): string
{
    $origin = cs_site_origin();
    $url = $origin . $path;
    $graph = [
        cs_business_schema($origin),
        [
            '@type' => 'WebSite',
            '@id' => $origin . '/#website',
            'name' => 'Creative Space',
            'url' => $origin . '/index.php',
            'inLanguage' => 'en-GB',
            'publisher' => ['@id' => $origin . '/#business'],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $url,
            'url' => $url,
            'name' => $title,
            'description' => $description,
            'isPartOf' => ['@id' => $origin . '/#website'],
            'about' => ['@id' => $origin . '/#business'],
            'inLanguage' => 'en-GB',
        ],
        ...$extra,
    ];
    return json_encode([
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
}
