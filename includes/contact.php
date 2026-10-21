<?php
$page = $GLOBALS['cs_page'] ?? cs_page([]);
$return = (string) ($page['path'] ?? 'index.php');
?>
			<section id="contact">
				<div class="contact-brand">
					<img src="/images/logo.png" alt="Creative Space — Private and group yoga classes" class="logo-mark" width="1355" height="1526" />
					<h2 class="visually-hidden">Contact Creative Space</h2>
				</div>
				<div class="inner">
					<section>
						<form method="post" action="/contact.php" accept-charset="UTF-8">
							<input type="hidden" name="return" value="<?php echo cs_h($return); ?>" />
							<label class="honeypot" aria-hidden="true">Leave blank<input type="text" name="website" tabindex="-1" autocomplete="off" /></label>
							<div class="fields">
								<div class="field half">
									<label for="name">Name</label>
									<input type="text" name="name" id="name" required maxlength="120" autocomplete="name" />
								</div>
								<div class="field half">
									<label for="email">Email</label>
									<input type="email" name="email" id="email" required maxlength="254" autocomplete="email" />
								</div>
								<div class="field">
									<label for="message">Message</label>
									<textarea name="message" id="message" rows="6" required maxlength="4000"></textarea>
								</div>
							</div>
							<ul class="actions">
								<li><input type="submit" value="Send Message" class="primary" /></li>
								<li><input type="reset" value="Clear" /></li>
							</ul>
						</form>
					</section>
					<section class="split">
						<section>
							<div class="contact-method">
								<span class="icon alt fa-envelope"></span>
								<h3>Email</h3>
								<a href="mailto:mycreativesspace.yoga@gmail.com">mycreativesspace.yoga@gmail.com</a>
							</div>
						</section>
						<section>
							<div class="contact-method">
								<span class="icon alt fa-phone"></span>
								<h3>Phone</h3>
								<span>07399 968655</span>
							</div>
						</section>
						<section>
							<div class="contact-method">
								<span class="icon alt fa-home"></span>
								<h3>Address</h3>
								<span>Vinay Soraya<br />
								20 Kingsland Road<br />
								Shoreditch / Hoxton<br />
								London E2 8DA</span>
							</div>
						</section>
					</section>
				</div>
			</section>
