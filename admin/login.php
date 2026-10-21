<?php

require dirname(__DIR__) . '/includes/auth.php';

$error = '';

if (cs_admin_logged_in()) {
    cs_redirect('/admin/recipes.php');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!cs_same_site_request() || !cs_csrf_ok() || !cs_rate_allow('login', 8, 600)) {
        $error = 'Those details did not match.';
    } else {
        $user = trim((string) ($_POST['username'] ?? ''));
        $pass = (string) ($_POST['password'] ?? '');
        if (strlen($user) <= 64 && strlen($pass) <= 200 && cs_try_login($user, $pass)) {
            cs_redirect('/admin/recipes.php');
        }
        $error = 'Those details did not match.';
        usleep(random_int(150000, 400000));
    }
}

?><!DOCTYPE HTML>
<html lang="en-GB">
	<head>
		<title>Admin login | Creative Space</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Security-Policy" content="<?php echo CS_CSP; ?>" />
		<meta name="robots" content="noindex, nofollow" />
		<link rel="icon" href="/images/favicon.ico" sizes="any" />
		<link rel="stylesheet" href="/assets/css/main.css" />
		<link rel="stylesheet" href="/assets/css/theme.css" />
	</head>
	<body class="is-preload">
		<div id="wrapper">
			<header id="header">
				<a href="/index.php" class="logo"><strong>Creative</strong> <span>Space</span></a>
			</header>
			<div id="main" class="alt">
				<section>
					<div class="inner">
						<header class="major">
							<h1>Studio admin</h1>
						</header>
						<?php if ($error !== ''): ?>
							<p class="form-note error"><?php echo cs_h($error); ?></p>
						<?php endif; ?>
						<form method="post" action="/admin/login.php" accept-charset="UTF-8" autocomplete="on">
							<?php echo cs_csrf_field(); ?>
							<div class="fields">
								<div class="field half">
									<label for="username">Username</label>
									<input type="text" name="username" id="username" required maxlength="64" autocomplete="username" />
								</div>
								<div class="field half">
									<label for="password">Password</label>
									<input type="password" name="password" id="password" required maxlength="200" autocomplete="current-password" />
								</div>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Log in" class="primary" /></li>
							</ul>
						</form>
					</div>
				</section>
			</div>
		</div>
		<script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/browser.min.js"></script>
		<script src="/assets/js/breakpoints.min.js"></script>
		<script src="/assets/js/util.js"></script>
		<script src="/assets/js/main.js"></script>
	</body>
</html>
