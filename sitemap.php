<?php

require __DIR__ . '/includes/security.php';
require __DIR__ . '/includes/seo.php';

cs_send_security_headers();
header('Content-Type: application/xml; charset=UTF-8');

$origin = cs_site_origin();
$pages = [
    ['path' => '/index.php', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['path' => '/yoga.php', 'priority' => '0.9', 'changefreq' => 'monthly'],
    ['path' => '/creative.php', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['path' => '/reviews.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['path' => '/vegan.php', 'priority' => '0.8', 'changefreq' => 'weekly'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as $page) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($origin . $page['path'], ENT_XML1) . "</loc>\n";
    echo '    <changefreq>' . $page['changefreq'] . "</changefreq>\n";
    echo '    <priority>' . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo '</urlset>';
