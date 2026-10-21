<?php
$page = $GLOBALS['cs_page'] ?? cs_page([]);
$path = '/' . ltrim((string) $page['path'], '/');
$schema = cs_json_ld($path, (string) $page['title'], (string) $page['description'], (array) ($page['schema_extra'] ?? []));
?><!DOCTYPE HTML>
<!--
	Creative Space — based on Forty by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html lang="en-GB">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="Content-Security-Policy" content="<?php echo CS_CSP; ?>" />
		<title><?php echo cs_h((string) $page['title']); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="<?php echo cs_h((string) $page['description']); ?>" />
		<meta name="author" content="Vinay Soraya" />
		<meta name="robots" content="index, follow" />
		<link rel="canonical" href="<?php echo cs_h($path); ?>" />
		<link rel="icon" href="/images/favicon.ico" sizes="any" />
		<link rel="icon" type="image/png" href="/images/favicon-32.png" sizes="32x32" />
		<link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />
		<link rel="manifest" href="/site.webmanifest" />
		<meta name="theme-color" content="#2f332c" />
		<meta property="og:type" content="website" />
		<meta property="og:site_name" content="Creative Space" />
		<meta property="og:locale" content="en_GB" />
		<meta property="og:title" content="<?php echo cs_h((string) $page['title']); ?>" />
		<meta property="og:description" content="<?php echo cs_h((string) $page['description']); ?>" />
		<meta property="og:url" content="<?php echo cs_h($path); ?>" />
		<meta property="og:image" content="/images/og-image.jpg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />
		<meta property="og:image:alt" content="Yoga poses outside a London facade at Creative Space" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:title" content="<?php echo cs_h((string) $page['title']); ?>" />
		<meta name="twitter:description" content="<?php echo cs_h((string) $page['twitter_description']); ?>" />
		<meta name="twitter:image" content="/images/og-image.jpg" />
		<link rel="stylesheet" href="/assets/css/main.css" />
		<link rel="stylesheet" href="/assets/css/theme.css" />
		<noscript><link rel="stylesheet" href="/assets/css/noscript.css" /></noscript>
		<script type="application/ld+json"><?php echo $schema; ?></script>
	</head>
	<body class="is-preload">
		<div id="wrapper">
