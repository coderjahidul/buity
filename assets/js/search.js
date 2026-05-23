/**
 * Basic search field enhancements.
 */
(function () {
	'use strict';

	document.querySelectorAll('.product-search__input').forEach(function (input) {
		input.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				input.value = '';
				input.blur();
			}
		});
	});
})();
