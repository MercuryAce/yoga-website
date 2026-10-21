<?php

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === basename(__FILE__)) {
    http_response_code(403);
    exit;
}

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/store.php';
require_once dirname(__DIR__) . '/data/config.php';

cs_send_admin_headers();
cs_boot_session();

const CS_ADMIN_IDLE = 14400;

function cs_admin_logged_in(): bool
{
    if (empty($_SESSION['cs_admin'])) {
        return false;
    }
    $seen = (int) ($_SESSION['cs_seen'] ?? 0);
    if ($seen > 0 && (time() - $seen) > CS_ADMIN_IDLE) {
        cs_session_clear();
        cs_boot_session();
        return false;
    }
    $_SESSION['cs_seen'] = time();
    return true;
}

function cs_require_admin(): void
{
    if (!cs_admin_logged_in()) {
        cs_redirect('/admin/login.php');
    }
}

function cs_try_login(string $user, string $pass): bool
{
    $userOk = hash_equals(CS_ADMIN_USER, $user);
    $passOk = password_verify($pass, CS_ADMIN_PASSWORD_HASH);
    if (!$userOk || !$passOk) {
        return false;
    }
    session_regenerate_id(true);
    $_SESSION['cs_admin'] = 1;
    $_SESSION['cs_seen'] = time();
    $_SESSION['cs_csrf'] = bin2hex(random_bytes(32));
    return true;
}
