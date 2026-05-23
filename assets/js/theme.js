/**
 * Mobile nav, categories dropdown, utilities.
 */
(function () {
	'use strict';

	var toggle = document.getElementById('menu-toggle');
	var body = document.body;

	if (toggle) {
		function setMenuOpen(open) {
			body.classList.toggle('primary-menu--open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		}

		toggle.addEventListener('click', function () {
			setMenuOpen(!body.classList.contains('primary-menu--open'));
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && body.classList.contains('primary-menu--open')) {
				setMenuOpen(false);
				toggle.focus();
			}
		});
	}

	var catToggle = document.querySelector('.header-categories__toggle');
	var catList = document.getElementById('header-categories-list');

	if (catToggle && catList) {
		catToggle.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = catList.hasAttribute('hidden');
			if (open) {
				catList.removeAttribute('hidden');
				catToggle.setAttribute('aria-expanded', 'true');
			} else {
				catList.setAttribute('hidden', '');
				catToggle.setAttribute('aria-expanded', 'false');
			}
		});

		document.addEventListener('click', function (e) {
			if (!catToggle.contains(e.target) && !catList.contains(e.target)) {
				catList.setAttribute('hidden', '');
				catToggle.setAttribute('aria-expanded', 'false');
			}
		});
	}
})();
