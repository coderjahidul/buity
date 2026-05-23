/**
 * AJAX add to cart toast and cart fragment helpers.
 */
(function ($) {
	'use strict';

	var $toast = $('#buity-toast');
	var toastTimer;

	function showToast(message) {
		if (!$toast.length) {
			return;
		}
		$toast.text(message).prop('hidden', false).addClass('is-visible');
		clearTimeout(toastTimer);
		toastTimer = setTimeout(function () {
			$toast.removeClass('is-visible');
			setTimeout(function () {
				$toast.prop('hidden', true);
			}, 300);
		}, 3000);
	}

	$(document.body).on('added_to_cart', function () {
		var msg = (window.buityCart && buityCart.i18n && buityCart.i18n.added) || 'Added to cart!';
		showToast(msg);
	});
})(jQuery);
