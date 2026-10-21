<?php
$page = $GLOBALS['cs_page'] ?? cs_page([]);
$gnarled = (($page['credit'] ?? 'standard') === 'gnarled');
?>
			<footer id="footer">
				<div class="inner">
					<ul class="icons">
						<li><a href="#" class="icon alt fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						<li><a href="#" class="icon alt fa-linkedin"><span class="label">LinkedIn</span></a></li>
					</ul>
					<ul class="copyright">
						<li>&copy; Creative Space 2026</li>
						<?php if ($gnarled): ?>
						<li class="gnarled-credit"><svg class="gnarled-tree" viewBox="0 0 28 36" width="16" height="21" aria-hidden="true" focusable="false"><g class="trunk" fill="none" stroke="currentColor" stroke-width="2.35" stroke-linecap="round" stroke-linejoin="round"><path class="stem" pathLength="100" d="M14 34.6c-1.1-4.6 2.3-7.2 1.5-12-1-5.6-3.7-6.4-2-11.8 1.3-4.3 3.1-5.6 2.2-9.2"/><path class="b1" pathLength="100" d="M13.1 21.6c-3.2-1.1-5.8.5-8 2.2"/><path class="b2" pathLength="100" d="M14.7 16.4c2.7-1.1 5.2.2 7.4-2.3"/><path class="b3" pathLength="100" d="M13.3 11.8c-2.3-2.1-3.1-5.2-1.6-7.6"/><path class="b4" pathLength="100" d="M15.2 10c2.3-1.5 4.8-.7 6.4-3.4"/></g><g class="sprout" fill="currentColor"><ellipse transform="rotate(-38 11.6 28.5)" cx="11.6" cy="28.5" rx="1.7" ry="1.05"/><ellipse transform="rotate(34 16.5 28.3)" cx="16.5" cy="28.3" rx="1.7" ry="1.05"/></g><g class="canopy" fill="currentColor"><ellipse cx="11.2" cy="3.9" rx="2.3" ry="2"/><ellipse cx="16.8" cy="2.9" rx="2.5" ry="2.1"/><ellipse cx="21.8" cy="6.3" rx="2.1" ry="1.8"/><ellipse cx="5.2" cy="23.9" rx="1.8" ry="1.55"/><ellipse cx="22.4" cy="13.6" rx="1.8" ry="1.55"/><circle cx="16.7" cy="22.7" r="1.2"/></g></svg>A GnarledTree production</li>
						<?php else: ?>
						<li>A HardHands production</li>
						<?php endif; ?>
						<li>Design: <a href="https://html5up.net" rel="noopener noreferrer">HTML5 UP</a></li>
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
