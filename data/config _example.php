<?php

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === basename(__FILE__)) {
    http_response_code(403);
    exit;
}

define('CS_MAIL_TO', '');
define('CS_SITE_ORIGIN', '');
define('CS_ADMIN_USER', '');
define('CS_ADMIN_PASSWORD_HASH', '');
