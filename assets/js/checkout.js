/**
 * Buity Checkout JS — Kiddomart design
 */
(function ($) {
	'use strict';

	/* ——— Address Tabs ——— */
	$(document).on('click', '.bco-addr-tab', function () {
		var $btn = $(this);

		// The form toggling for 'add' is handled in form-checkout.php inline script
		if ($btn.hasClass('bco-addr-tab--add')) {
			return;
		}

		$('.bco-addr-tab').removeClass('bco-addr-tab--active').attr('aria-selected', 'false');
		$btn.addClass('bco-addr-tab--active').attr('aria-selected', 'true');
	});

	/* ——— Payment Method Toggle ——— */
	$(document).on('change', '.bco-payment-radio', function () {
		$('.bco-payment-method').removeClass('bco-payment-method--active');
		$(this).closest('.bco-payment-method').addClass('bco-payment-method--active');
	});

	// Also handle label click for browsers that don't auto-check
	$(document).on('click', '.bco-payment-method', function () {
		var $radio = $(this).find('.bco-payment-radio');
		$('.bco-payment-method').removeClass('bco-payment-method--active');
		$(this).addClass('bco-payment-method--active');
		$radio.prop('checked', true).trigger('change');
	});

	/* ——— Coupon Form Toggle ——— */
	$(document).on('click', '#bco-coupon-toggle, #bco-coupon-apply-link', function (e) {
		e.preventDefault();
		var $form = $('#bco-coupon-form');
		if ($form.prop('hidden')) {
			$form.removeAttr('hidden');
			$('#bco-coupon-input').focus();
		} else {
			$form.attr('hidden', true);
		}
	});

	/* ——— Coupon Apply via AJAX ——— */
	$(document).on('click', '#bco-coupon-submit', function () {
		var code = $('#bco-coupon-input').val().trim();
		if (!code) return;

		var $btn = $(this).prop('disabled', true).text('Applying…');

		$.post(
			(typeof wc_checkout_params !== 'undefined' ? wc_checkout_params.wc_ajax_url : '/wp-admin/admin-ajax.php').replace('%%endpoint%%', 'apply_coupon'),
			{ coupon_code: code, security: (typeof wc_checkout_params !== 'undefined' ? wc_checkout_params.apply_coupon_nonce : '') },
			function (response) {
				$('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
				if (response) {
					$('.bco-wrap').prepend(response);
				}
				$btn.prop('disabled', false).text('Apply');
				$(document.body).trigger('update_checkout');
				refreshTotals();
			}
		);
	});

	/* ——— Quantity Buttons ——— */
	$(document).on('click', '.bco-qty__btn', function () {
		var $btn = $(this);
		var action = $btn.data('action');
		var key = $btn.data('key');
		var $num = $('#bco_qty_' + key);
		var current = parseInt($num.text(), 10) || 1;
		var next = action === 'plus' ? current + 1 : Math.max(1, current - 1);

		if (next === current) return;
		$num.text(next);

		updateCartItemQty(key, next);
	});

	/* ——— Remove Item ——— */
	$(document).on('click', '.bco-item__remove', function () {
		var key = $(this).data('key');
		var $row = $(this).closest('.bco-item');
		$row.fadeOut(250, function () { $row.remove(); });
		updateCartItemQty(key, 0);
	});

	/* ——— AJAX: update cart item quantity ——— */
	function updateCartItemQty(key, qty) {
		$.post(
			bcoData.ajaxUrl,
			{
				action: 'bco_update_cart_item',
				key: key,
				qty: qty,
				nonce: bcoData.nonce
			},
			function (response) {
				if (response && response.success) {
					refreshTotals(response.data);
					$(document.body).trigger('wc_fragment_refresh');
					$(document.body).trigger('update_checkout');
				}
			}
		);
	}

	/* ——— Refresh totals in UI from AJAX response ——— */
	function refreshTotals(data) {
		if (!data) return;
		if (data.subtotal) $('[data-total="subtotal"]').html(data.subtotal);
		if (data.discount) $('[data-total="discount"]').html(data.discount);
		if (data.shipping) $('[data-total="shipping"]').html(data.shipping);
		if (data.grand_total) $('[data-total="grand_total"]').html(data.grand_total);
	}

	/* ——— Shipping method change → update_checkout ——— */
	$(document).on('change', '.bco-shipping-select', function () {
		$(document.body).trigger('update_checkout');
	});

	/* ——— Listen to WC checkout_error to scroll to notice ——— */
	$(document.body).on('checkout_error', function () {
		var $err = $('.woocommerce-error').first();
		if ($err.length) {
			$('html, body').animate({ scrollTop: $err.offset().top - 80 }, 400);
		}
	});

	/* ——— After WC updates checkout (e.g. shipping changes), sync totals ——— */
	$(document.body).on('updated_checkout', function () {
		// Re-apply active class to selected payment
		var $checked = $('.bco-payment-radio:checked');
		$('.bco-payment-method').removeClass('bco-payment-method--active');
		$checked.closest('.bco-payment-method').addClass('bco-payment-method--active');
	});

}(jQuery));
