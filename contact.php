<?php

require __DIR__ . '/includes/security.php';
require __DIR__ . '/includes/store.php';
require __DIR__ . '/data/config.php';

cs_send_security_headers();

function cs_safe_return(?string $raw): string
{
    $path = parse_url((string) $raw, PHP_URL_PATH) ?: 'index.php';
    $path = ltrim((string) $path, '/');
    $legacy = [
        'index.html' => 'index.php',
        'yoga.html' => 'yoga.php',
        'creative.html' => 'creative.php',
        'reviews.html' => 'reviews.php',
        'vegan.html' => 'vegan.php',
    ];
    $path = $legacy[$path] ?? $path;
    $allowed = ['index.php', 'yoga.php', 'creative.php', 'reviews.php', 'vegan.php'];
    return in_array($path, $allowed, true) ? $path : 'index.php';
}

function cs_contact_fail(string $return): void
{
    cs_redirect($return . '?error=1#contact');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    cs_redirect('/index.php');
}

$return = cs_safe_return($_POST['return'] ?? 'index.php');

if (!cs_same_site_request()) {
    cs_contact_fail($return);
}

$cookie = (string) ($_COOKIE['cs_csrf'] ?? '');
$posted = (string) ($_POST['csrf'] ?? '');
if ($cookie !== '' && ($posted === '' || !hash_equals($cookie, $posted))) {
    cs_contact_fail($return);
}

if (trim((string) ($_POST['website'] ?? '')) !== '') {
    cs_redirect($return . '?sent=1#contact');
}

if (!cs_rate_allow('contact', 5, 600)) {
    cs_contact_fail($return);
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if (strlen($name) > 120 || strlen($email) > 254 || strlen($message) > 4000) {
    cs_contact_fail($return);
}

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    cs_contact_fail($return);
}

$entry = [
    'id' => uniqid('msg-', true),
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'page' => $return,
    'received_at' => gmdate('c'),
];

$messages = cs_load_json('messages.json', []);
array_unshift($messages, $entry);
$messages = array_slice($messages, 0, 200);
cs_save_json('messages.json', $messages);

$safeName = cs_header_safe(substr($name, 0, 80));
$safeEmail = cs_header_safe($email);
$subject = cs_header_safe('Creative Space enquiry from ' . $safeName);
$body = "Name: {$name}\nEmail: {$email}\nPage: {$return}\n\n{$message}\n";
$headers = [
    'From: Creative Space <no-reply@localhost>',
    'Reply-To: ' . $safeEmail,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: CreativeSpace',
];
@mail(CS_MAIL_TO, $subject, $body, implode("\r\n", $headers));

cs_redirect($return . '?sent=1#contact');
