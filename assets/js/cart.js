/**
 * AJAX add to cart — success notice bar and cart fragment helpers.
 */
(function ($) {
	'use strict';

	var $notice = $('#buity-cart-notice');
	var hideTimer;

	function hideNotice() {
		if (!$notice.length) {
			return;
		}
		$notice.removeClass('is-visible');
		clearTimeout(hideTimer);
		hideTimer = setTimeout(function () {
			$notice.prop('hidden', true);
		}, 350);
	}

	function showNotice() {
		if (!$notice.length) {
			return;
		}

		var i18n = (window.buityCart && buityCart.i18n) || {};
		var urls = (window.buityCart && buityCart.urls) || {};

		if (i18n.success) {
			$notice.find('.buity-cart-notice__title').text(i18n.success);
		}
		if (urls.cart) {
			$notice.find('.buity-cart-notice__view-cart').attr('href', urls.cart);
		}
		if (urls.checkout) {
			$notice.find('.buity-cart-notice__buy-now').attr('href', urls.checkout);
		}

		$notice.prop('hidden', false);
		// Force reflow so transition runs when re-showing.
		$notice[0].offsetHeight;
		$notice.addClass('is-visible');

		clearTimeout(hideTimer);
		hideTimer = setTimeout(hideNotice, 8000);
	}

	if ($notice.length) {
		$notice.on('click', '.buity-cart-notice__close', function (e) {
			e.preventDefault();
			hideNotice();
		});
	}

	function removeViewCartLinks() {
		$('.added_to_cart').remove();
	}

	$(document.body).on('added_to_cart wc_cart_button_updated', function (e, fragments, cart_hash, $button) {
		if ($button && $button.length) {
			$button.siblings('.added_to_cart').remove();
		}
		removeViewCartLinks();
	});

	$(document.body).on('added_to_cart', function () {
		$('.woocommerce-notices-wrapper .woocommerce-message').remove();
		showNotice();
	});

	$(document.body).on('wc_fragments_refreshed', function () {
		$('.woocommerce-notices-wrapper .woocommerce-message').remove();
	});
})(jQuery);
