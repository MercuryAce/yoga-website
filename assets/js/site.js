(function () {
	function csrfToken() {
		var name = 'cs_csrf=';
		var parts = document.cookie.split('; ');
		for (var i = 0; i < parts.length; i++) {
			if (parts[i].indexOf(name) === 0) {
				return decodeURIComponent(parts[i].slice(name.length));
			}
		}
		return '';
	}

	function randomToken() {
		if (!window.crypto || !window.crypto.getRandomValues) {
			return '';
		}
		var bytes = new Uint8Array(32);
		window.crypto.getRandomValues(bytes);
		return Array.from(bytes, function (b) {
			return ('0' + b.toString(16)).slice(-2);
		}).join('');
	}

	function ensureContactCsrf() {
		var token = csrfToken();
		if (!token || token.length < 32) {
			token = randomToken();
			var secure = location.protocol === 'https:' ? '; Secure' : '';
			document.cookie = 'cs_csrf=' + token + '; Path=/; SameSite=Strict' + secure;
		}
		document.querySelectorAll('form[action="contact.php"], form[action="/contact.php"]').forEach(function (form) {
			var input = form.querySelector('input[name="csrf"]');
			if (!input) {
				input = document.createElement('input');
				input.type = 'hidden';
				input.name = 'csrf';
				form.appendChild(input);
			}
			input.value = token;
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		window.addEventListener('hashchange', function () {
			var wrapper = document.getElementById('wrapper');
			if (wrapper)
				wrapper.classList.remove('is-transitioning');
			document.querySelectorAll('.is-transitioning').forEach(function (el) {
				el.classList.remove('is-transitioning');
			});
		});

		ensureContactCsrf();

		document.querySelectorAll('form[data-confirm]').forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!window.confirm(form.getAttribute('data-confirm'))) {
					event.preventDefault();
				}
			});
		});

		var params = new URLSearchParams(window.location.search);
		var form = document.querySelector('#contact form');
		var tree = document.querySelector('#footer .gnarled-credit');
		if (tree) {
			var growTree = function () {
				tree.classList.add('is-grown');
			};
			if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
				growTree();
			} else {
				var observer = new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							growTree();
							observer.disconnect();
						}
					});
				}, {
					threshold: 0.2
				});
				observer.observe(tree);
			}
		}

		if (form && (params.get('sent') === '1' || params.get('error') === '1')) {
			var note = form.querySelector('.form-note') || document.createElement('p');
			note.className = 'form-note' + (params.get('error') === '1' ? ' error' : '');
			note.textContent = params.get('error') === '1'
				? 'Please add your name, a valid email, and a message.'
				: 'Thanks — your message has been sent. Vinay will get back to you by email or phone.';
			form.appendChild(note);
		}
	});
})();
