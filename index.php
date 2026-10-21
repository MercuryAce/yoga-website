<?php
require __DIR__ . '/includes/layout.php';
cs_layout_start([
    'path' => 'index.php',
    'title' => 'Creative Space | Private & group yoga in Shoreditch, London',
    'description' => 'Private and group yoga with Vinay Soraya at Creative Space in Shoreditch. Small classes, creative collaborations, and a vegan kitchen on Kingsland Road.',
    'twitter_description' => 'Private and group yoga with Vinay Soraya at Creative Space in Shoreditch.',
    'header_class' => 'alt',
    'credit' => 'gnarled',
]);
?>
				<section id="banner" class="major">
					<div class="inner">
						<header class="major">
							<h1>Creative Space</h1>
						</header>
						<div class="content">
							<p>Private and group yoga with Vinay Soraya<br />
							in Shoreditch, London.</p>
							<ul class="actions">
								<li><a href="#one" class="button next scrolly">Explore</a></li>
							</ul>
						</div>
					</div>
				</section>
				<div id="main">
					<section id="one" class="tiles">
						<article>
							<span class="image">
								<img src="/images/cartoon/triangle.jpg" alt="Vinay in triangle pose on a Shoreditch rooftop" />
							</span>
							<header class="major">
								<h3><a href="/yoga.php" class="link">Yoga</a></h3>
								<p>Private and group classes</p>
							</header>
						</article>
						<article>
							<span class="image">
								<img src="/images/cartoon/side-angle.jpg" alt="Vinay in extended side angle pose" />
							</span>
							<header class="major">
								<h3><a href="/creative.php" class="link">Creative Collective</a></h3>
								<p>Artists, photographers, collaborations</p>
							</header>
						</article>
						<article>
							<span class="image">
								<img src="/images/cartoon/warrior.jpg" alt="Vinay in a warrior-style lunge" />
							</span>
							<header class="major">
								<h3><a href="/reviews.php" class="link">Reviews</a></h3>
								<p>What students say</p>
							</header>
						</article>
						<article>
							<span class="image">
								<img src="/images/cartoon/standing-balance.jpg" alt="Vinay in a standing balance pose" loading="lazy" decoding="async" />
							</span>
							<header class="major">
								<h3><a href="/yoga.php#events" class="link">Events</a></h3>
								<p>Rooftop sessions and workshops</p>
							</header>
						</article>
						<article>
							<span class="image">
								<img src="/images/cartoon/one-leg-wheel.jpg" alt="Vinay in a one-legged wheel pose" loading="lazy" decoding="async" />
							</span>
							<header class="major">
								<h3><a href="#about" class="link">About Vinay</a></h3>
								<p>Meet Vinay Soraya</p>
							</header>
						</article>
						<article>
							<span class="image">
								<img src="/images/cartoon/wheel-close.jpg" alt="Vinay in a wheel backbend" loading="lazy" decoding="async" />
							</span>
							<header class="major">
								<h3><a href="#contact" class="link">Contact</a></h3>
								<p>Book a class in Shoreditch</p>
							</header>
						</article>
					</section>
					<section id="two">
						<div class="inner">
							<img src="/images/logo.png" alt="Creative Space logo" class="logo-mark" width="1355" height="1526" />
							<header class="major">
								<h2>Be creative</h2>
							</header>
							<p>Creative Space is Vinay Soraya’s studio in Shoreditch: a calm room for private and group yoga, and a meeting point for artists, photographers, and collaborators. Classes are small, practical, and built around the body in front of you — not a packed timetable.</p>
							<ul class="themes">
								<li>
									<a href="/yoga.php">
										<span class="icon fa-heart"></span>
										<h3>Yoga</h3>
									</a>
								</li>
								<li>
									<a href="/vegan.php">
										<span class="icon fa-leaf"></span>
										<h3>Veganism</h3>
									</a>
								</li>
								<li>
									<a href="/creative.php">
										<span class="icon fa-camera"></span>
										<h3>Collective</h3>
									</a>
								</li>
							</ul>
						</div>
					</section>
					<section id="about">
						<div class="inner">
							<header class="major">
								<h2>About Vinay Soraya</h2>
							</header>
							<p>Vinay teaches private and group yoga from Creative Space on Kingsland Road. Sessions mix alignment, breath, and enough play to keep practice honest — whether you are new to the mat or coming back after a long gap. She also hosts rooftop and city-location shoots with the Creative Collective, and cooks from a vegan kitchen when the studio fills with people after class.</p>
							<ul class="actions">
								<li><a href="/yoga.php" class="button next">See classes</a></li>
							</ul>
						</div>
					</section>
				</div>
<?php
cs_layout_end();
