<?php

if (basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? '')) === basename(__FILE__)) {
    http_response_code(403);
    exit;
}

function cs_admin_header(string $title): void
{
    echo '<!DOCTYPE HTML>
<html lang="en-GB">
	<head>
		<title>' . cs_h($title) . ' | Creative Space admin</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Security-Policy" content="' . CS_CSP . '" />
		<meta name="robots" content="noindex, nofollow" />
		<link rel="icon" href="/images/favicon.ico" sizes="any" />
		<link rel="stylesheet" href="/assets/css/main.css" />
		<link rel="stylesheet" href="/assets/css/theme.css" />
	</head>
	<body class="is-preload">
		<div id="wrapper">
			<header id="header">
				<a href="/index.php" class="logo"><strong>Creative</strong> <span>Space</span></a>
				<nav>
					<a href="#menu">Menu</a>
				</nav>
			</header>
			<nav id="menu">
				<ul class="links">
					<li><a href="/admin/index.php">Inbox</a></li>
					<li><a href="/admin/recipes.php">Recipes</a></li>
					<li><a href="/admin/recipe-edit.php">Add recipe</a></li>
					<li><a href="/vegan.php">View kitchen</a></li>
					<li><a href="/admin/logout.php">Log out</a></li>
				</ul>
			</nav>
			<div id="main" class="alt">
				<section>
					<div class="inner">
';
}

function cs_admin_footer(): void
{
    echo '					</div>
				</section>
			</div>
			<footer id="footer">
				<div class="inner">
					<ul class="copyright">
						<li>&copy; Creative Space 2026</li>
						<li>Admin</li>
					</ul>
				</div>
			</footer>
		</div>
		<script src="/assets/js/jquery.min.js"></script>
		<script src="/assets/js/jquery.scrolly.min.js"></script>
		<script src="/assets/js/jquery.scrollex.min.js"></script>
		<script src="/assets/js/browser.min.js"></script>
		<script src="/assets/js/breakpoints.min.js"></script>
		<script src="/assets/js/util.js"></script>
		<script src="/assets/js/main.js"></script>
		<script src="/assets/js/site.js"></script>
	</body>
</html>
';
}

function cs_lines_to_list(string $text, int $maxLines = 80, int $maxLen = 300): array
{
    $lines = preg_split('/\r\n|\r|\n/', $text) ?: [];
    $out = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $out[] = substr($line, 0, $maxLen);
        if (count($out) >= $maxLines) {
            break;
        }
    }
    return $out;
}
