<?php

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === basename(__FILE__)) {
    http_response_code(403);
    exit;
}

require_once __DIR__ . '/security.php';

function cs_data_dir(): string
{
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $uploads = $dir . '/uploads';
    if (!is_dir($uploads)) {
        mkdir($uploads, 0775, true);
    }
    return $dir;
}

function cs_recipe_images_dir(): string
{
    $dir = dirname(__DIR__) . '/images/recipes';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    return $dir;
}

function cs_json_name(string $file): string
{
    $base = basename($file);
    if (!preg_match('/^[a-z0-9][a-z0-9._-]*\.json$/', $base)) {
        throw new InvalidArgumentException('Invalid data file');
    }
    return $base;
}

function cs_load_json(string $file, array $default = []): array
{
    $path = cs_data_dir() . '/' . cs_json_name($file);
    if (!is_file($path)) {
        return $default;
    }
    $data = json_decode((string) file_get_contents($path), true);
    return is_array($data) ? $data : $default;
}

function cs_save_json(string $file, array $data): void
{
    $path = cs_data_dir() . '/' . cs_json_name($file);
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        throw new RuntimeException('Could not encode ' . $file);
    }
    $fp = fopen($path, 'c+');
    if ($fp === false) {
        throw new RuntimeException('Could not open ' . $file);
    }
    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, $json);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}

function cs_rate_allow(string $bucket, int $limit, int $window): bool
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0');
    $key = hash('sha256', $bucket . '|' . $ip);
    $now = time();
    $data = cs_load_json('rate-limit.json', []);
    foreach ($data as $k => $times) {
        $kept = array_values(array_filter((array) $times, static fn($t) => (int) $t > $now - $window));
        if ($kept === []) {
            unset($data[$k]);
        } else {
            $data[$k] = $kept;
        }
    }
    $hits = $data[$key] ?? [];
    if (count($hits) >= $limit) {
        cs_save_json('rate-limit.json', $data);
        return false;
    }
    $hits[] = $now;
    $data[$key] = $hits;
    cs_save_json('rate-limit.json', $data);
    return true;
}

function cs_store_upload_image(string $tmp, string $filename, int $type): ?string
{
    $filename = basename($filename);
    if (!preg_match('/^[a-z0-9-]+\.(jpg|png|webp)$/', $filename)) {
        return null;
    }
    if (!is_uploaded_file($tmp) || !is_file($tmp)) {
        return null;
    }
    if (filesize($tmp) > 2 * 1024 * 1024) {
        return null;
    }
    $allowed = [
        IMAGETYPE_JPEG => 'image/jpeg',
        IMAGETYPE_PNG => 'image/png',
        IMAGETYPE_WEBP => 'image/webp',
    ];
    if (!isset($allowed[$type])) {
        return null;
    }
    if (class_exists('finfo')) {
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        if ($mime !== $allowed[$type]) {
            return null;
        }
    }
    $dir = realpath(cs_recipe_images_dir());
    if ($dir === false) {
        return null;
    }
    $dest = $dir . DIRECTORY_SEPARATOR . $filename;
    $saved = false;
    if (extension_loaded('gd')) {
        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($tmp),
            IMAGETYPE_PNG => @imagecreatefrompng($tmp),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($tmp) : false,
            default => false,
        };
        if ($image !== false) {
            $saved = match ($type) {
                IMAGETYPE_JPEG => imagejpeg($image, $dest, 90),
                IMAGETYPE_PNG => imagepng($image, $dest, 6),
                IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($image, $dest, 90) : false,
                default => false,
            };
            imagedestroy($image);
        }
    }
    if (!$saved && !move_uploaded_file($tmp, $dest)) {
        return null;
    }
    if (!is_file($dest)) {
        return null;
    }
    chmod($dest, 0644);
    return 'images/recipes/' . $filename;
}

function cs_slug(string $title): string
{
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? $slug;
    $slug = trim($slug, '-');
    $slug = substr($slug, 0, 80);
    return cs_valid_slug($slug) ? $slug : uniqid('recipe-', false);
}

function cs_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
