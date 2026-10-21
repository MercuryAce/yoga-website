<?php

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath((string) $_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    http_response_code(403);
    exit;
}

const CS_CSP = "default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'self'; form-action 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; connect-src 'self'";

function cs_is_https(): bool
{
    $forwarded = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    return $forwarded === 'https'
        || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
}

function cs_send_security_headers(): void
{
    $remote = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
    if (!in_array($remote, ['127.0.0.1', '::1'], true)) {
        ini_set('display_errors', '0');
    }
    if (headers_sent()) {
        return;
    }
    header_remove('X-Powered-By');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Content-Security-Policy: ' . CS_CSP);
    if (cs_is_https()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

function cs_send_admin_headers(): void
{
    cs_send_security_headers();
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
    }
}

function cs_boot_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = cs_is_https();
    session_name('csadmin');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/admin',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

function cs_session_clear(): void
{
    cs_boot_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Strict',
        ]);
    }
    session_destroy();
}

function cs_csrf_token(): string
{
    cs_boot_session();
    $token = $_SESSION['cs_csrf'] ?? '';
    if (!is_string($token) || strlen($token) < 32) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['cs_csrf'] = $token;
    }
    return $token;
}

function cs_csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(cs_csrf_token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" />';
}

function cs_csrf_ok(): bool
{
    cs_boot_session();
    $have = (string) ($_SESSION['cs_csrf'] ?? '');
    $sent = (string) ($_POST['csrf'] ?? '');
    return $have !== '' && hash_equals($have, $sent);
}

function cs_request_authority(): string
{
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if ($host === '') {
        return '';
    }
    if (str_contains($host, ':')) {
        return $host;
    }
    return $host . ':' . (cs_is_https() ? '443' : '80');
}

function cs_url_authority(string $url): string
{
    $parts = parse_url($url);
    if (!is_array($parts) || empty($parts['host'])) {
        return '';
    }
    $host = strtolower((string) $parts['host']);
    $port = $parts['port'] ?? null;
    if ($port === null) {
        $scheme = strtolower((string) ($parts['scheme'] ?? 'http'));
        $port = $scheme === 'https' ? 443 : 80;
    }
    return $host . ':' . $port;
}

function cs_same_site_request(): bool
{
    $here = cs_request_authority();
    if ($here === '') {
        return false;
    }
    $origin = (string) ($_SERVER['HTTP_ORIGIN'] ?? '');
    if ($origin !== '') {
        return cs_url_authority($origin) === $here;
    }
    $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
    if ($referer !== '') {
        return cs_url_authority($referer) === $here;
    }
    return false;
}

function cs_header_safe(string $value): string
{
    return str_replace(["\r", "\n", "\0"], '', $value);
}

function cs_valid_slug(string $slug): bool
{
    return (bool) preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)
        && strlen($slug) <= 80;
}

function cs_safe_image_src(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $path = ltrim($path, '/');
    if ($path === '' || str_contains($path, '..')) {
        return '';
    }
    if (!preg_match('#^images/[a-zA-Z0-9._/-]+\.(jpe?g|png|webp)$#', $path)) {
        return '';
    }
    return '/' . $path;
}

function cs_redirect(string $path): void
{
    $path = cs_header_safe($path);
    if ($path === '' || preg_match('#^[a-z][a-z0-9+.-]*:#i', $path) || str_contains($path, "\n")) {
        $path = '/index.php';
    }
    if (!str_starts_with($path, '/')) {
        $path = '/' . ltrim($path, '/');
    }
    header('Location: ' . $path);
    exit;
}
