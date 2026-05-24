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

	// ——— Classic cart page AJAX quantity & item removal updates ———
	$(document).on('click', '.buity-qty-minus, .buity-qty-plus', function (e) {
		e.preventDefault();
		var $btn = $(this);
		var action = $btn.data('action');
		var key = $btn.data('key');
		var $input = $('#qty_' + key);
		var val = parseInt($input.val(), 10) || 1;
		var step = parseInt($input.attr('step'), 10) || 1;
		var min = parseInt($input.attr('min'), 10) || 1;
		var max = parseInt($input.attr('max'), 10) || 9999;
		
		var newVal = val;
		if (action === 'minus') {
			newVal = Math.max(min, val - step);
		} else {
			newVal = Math.min(max, val + step);
		}
		
		if (newVal !== val) {
			$input.val(newVal);
			updateCartItemQty(key, newVal);
		}
	});

	$(document).on('change', '.buity-qty-input', function () {
		var $input = $(this);
		var key = $input.attr('id').replace('qty_', '');
		var val = parseInt($input.val(), 10) || 1;
		var min = parseInt($input.attr('min'), 10) || 1;
		var max = parseInt($input.attr('max'), 10) || 9999;
		
		if (val < min) {
			val = min;
			$input.val(val);
		} else if (val > max) {
			val = max;
			$input.val(val);
		}
		
		updateCartItemQty(key, val);
	});

	$(document).on('click', '.buity-remove-item', function (e) {
		e.preventDefault();
		var $btn = $(this);
		var key = $btn.data('key');
		
		$btn.closest('.buity-cart-item').css('opacity', '0.5');
		updateCartItemQty(key, 0);
	});

	function updateCartItemQty(key, qty) {
		if (!window.buityCart || !buityCart.ajaxUrl) {
			return;
		}

		var $cartWrap = $('.buity-cart-page-wrap');
		$cartWrap.css('pointer-events', 'none').css('opacity', '0.7');

		$.ajax({
			type: 'POST',
			url: buityCart.ajaxUrl,
			data: {
				action: 'bco_update_cart_item',
				key: key,
				qty: qty,
				nonce: buityCart.nonce
			},
			success: function (response) {
				$cartWrap.css('pointer-events', '').css('opacity', '');
				if (response.success) {
					var data = response.data;
					
					if (qty === 0) {
						var $row = $('.buity-cart-item[data-key="' + key + '"]');
						$row.slideUp(200, function () {
							$row.remove();
							if (data.count === 0) {
								window.location.reload();
							}
						});
					} else {
						var $row = $('.buity-cart-item[data-key="' + key + '"]');
						if (data.item_subtotal) {
							$row.find('.buity-item-subtotal-val').html(data.item_subtotal);
						}
					}

					// Update order summary
					$('.buity-summary-subtotal').html(data.subtotal);
					$('.buity-summary-total-val').html(data.grand_total);
					
					var hasDiscount = false;
					if (data.discount) {
						var cleanDiscount = parseFloat(data.discount.replace(/[^\d\.]/g, ''));
						if (!isNaN(cleanDiscount) && cleanDiscount > 0) {
							hasDiscount = true;
						}
					}
					
					if (hasDiscount) {
						$('.buity-summary-discount').html(data.discount);
						$('.buity-summary-row-discount').show();
					} else {
						$('.buity-summary-row-discount').hide();
					}

					// Update badge count
					$('#buity-cart-count-badge').text(data.count === 1 ? '1 Item' : data.count + ' Items');
					
					// Trigger fragments update to sync header cart
					$(document.body).trigger('removed_from_cart'); 
				} else {
					console.error(response.data ? response.data.message : 'Error updating cart');
					window.location.reload();
				}
			},
			error: function () {
				$cartWrap.css('pointer-events', '').css('opacity', '');
				window.location.reload();
			}
		});
	}
})(jQuery);
