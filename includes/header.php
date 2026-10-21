<?php
$page = $GLOBALS['cs_page'] ?? cs_page([]);
$headerClass = trim(' ' . (string) ($page['header_class'] ?? ''));
$about = (string) ($page['about_href'] ?? '/index.php#about');
?>
			<header id="header"<?php echo $headerClass !== '' ? ' class="' . cs_h(ltrim($headerClass)) . '"' : ''; ?>>
				<a href="/index.php" class="logo"><strong>Creative</strong> <span>Space</span></a>
				<nav>
					<a href="#menu">Menu</a>
				</nav>
			</header>
			<nav id="menu">
				<ul class="links">
					<li><a href="/index.php">Home</a></li>
					<li><a href="/yoga.php">Yoga</a></li>
					<li><a href="/creative.php">Creative</a></li>
					<li><a href="/reviews.php">Reviews</a></li>
					<li><a href="<?php echo cs_h($about); ?>">About</a></li>
					<li><a href="#contact">Contact</a></li>
				</ul>
				<ul class="actions stacked">
					<li><a href="#contact" class="button primary fit">Book a class</a></li>
				</ul>
			</nav>
