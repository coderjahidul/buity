/**
 * Single product: qty steppers, Buy Now, gallery thumb nav, See More.
 */
(function () {
	'use strict';

	var buyNowFlag = document.querySelector('.sp-buy-now-flag');
	var buyNowBtn = document.querySelector('.sp-btn--buy-now');
	var cartForm = document.querySelector('.sp-cart-form');

	if (buyNowBtn && buyNowFlag && cartForm) {
		buyNowBtn.addEventListener('click', function () {
			buyNowFlag.value = '1';
		});

		var addBtn = cartForm.querySelector('.sp-btn--cart');
		if (addBtn) {
			addBtn.addEventListener('click', function () {
				buyNowFlag.value = '0';
			});
		}
	}

	document.querySelectorAll('.sp-cart-form .quantity').forEach(function (wrap) {
		if (wrap.querySelector('.sp-qty__btn')) {
			return;
		}

		var input = wrap.querySelector('.qty');
		if (!input) {
			return;
		}

		var minus = document.createElement('button');
		minus.type = 'button';
		minus.className = 'sp-qty__btn sp-qty__btn--minus';
		minus.setAttribute('aria-label', 'Decrease quantity');
		minus.textContent = '−';

		var plus = document.createElement('button');
		plus.type = 'button';
		plus.className = 'sp-qty__btn sp-qty__btn--plus';
		plus.setAttribute('aria-label', 'Increase quantity');
		plus.textContent = '+';

		wrap.insertBefore(minus, input);
		wrap.appendChild(plus);

		function step(delta) {
			var min = parseFloat(input.getAttribute('min')) || 1;
			var max = parseFloat(input.getAttribute('max')) || Infinity;
			var val = parseFloat(input.value) || min;
			val = Math.min(max, Math.max(min, val + delta));
			input.value = val;
			input.dispatchEvent(new Event('change', { bubbles: true }));
		}

		minus.addEventListener('click', function () {
			step(-1);
		});
		plus.addEventListener('click', function () {
			step(1);
		});
	});

	function wrapGalleryThumbs() {
		var gallery = document.querySelector('.sp-gallery .woocommerce-product-gallery');
		if (!gallery) {
			return;
		}

		var thumbs = gallery.querySelector('.flex-control-thumbs');
		if (!thumbs || thumbs.closest('.sp-gallery__thumbs-wrap')) {
			return;
		}

		var wrap = document.createElement('div');
		wrap.className = 'sp-gallery__thumbs-wrap';

		var prev = document.createElement('button');
		prev.type = 'button';
		prev.className = 'sp-gallery__nav sp-gallery__nav--prev';
		prev.setAttribute('aria-label', 'Previous image');
		prev.innerHTML = '&#8249;';

		var next = document.createElement('button');
		next.type = 'button';
		next.className = 'sp-gallery__nav sp-gallery__nav--next';
		next.setAttribute('aria-label', 'Next image');
		next.innerHTML = '&#8250;';

		thumbs.parentNode.insertBefore(wrap, thumbs);
		wrap.appendChild(prev);
		wrap.appendChild(thumbs);
		wrap.appendChild(next);

		function getItems() {
			return Array.prototype.slice.call(thumbs.querySelectorAll('li'));
		}

		function getActiveIndex() {
			var items = getItems();
			var activeImg = thumbs.querySelector('img.flex-active');
			if (activeImg) {
				var li = activeImg.closest('li');
				var idx = items.indexOf(li);
				if (idx >= 0) {
					return idx;
				}
			}
			return 0;
		}

		function goTo(index) {
			var items = getItems();
			if (!items.length) {
				return;
			}
			var i = Math.max(0, Math.min(index, items.length - 1));
			var link = items[i].querySelector('a, img');
			if (link) {
				link.click();
			}
			items[i].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
			prev.disabled = i <= 0;
			next.disabled = i >= items.length - 1;
		}

		prev.addEventListener('click', function () {
			goTo(getActiveIndex() - 1);
		});
		next.addEventListener('click', function () {
			goTo(getActiveIndex() + 1);
		});

		thumbs.addEventListener('click', function () {
			window.setTimeout(function () {
				var idx = getActiveIndex();
				prev.disabled = idx <= 0;
				next.disabled = idx >= getItems().length - 1;
			}, 50);
		});

		goTo(0);
	}

	function initGallery() {
		wrapGalleryThumbs();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initGallery);
	} else {
		initGallery();
	}

	window.setTimeout(initGallery, 400);
	window.setTimeout(initGallery, 1200);

	if (typeof jQuery !== 'undefined') {
		jQuery(document.body).on('wc-product-gallery-after-init', initGallery);
	}

	document.querySelectorAll('[data-sp-see-more]').forEach(function (btn) {
		var panel = btn.closest('.sp-tabs__panel');
		var inner = panel ? panel.querySelector('[data-sp-tab-panel]') : null;
		if (!inner) {
			return;
		}

		function updateSeeMore() {
			if (inner.scrollHeight <= 280) {
				btn.hidden = true;
				inner.classList.add('is-expanded');
				return;
			}
			btn.hidden = false;
		}

		updateSeeMore();
		window.addEventListener('resize', updateSeeMore);

		btn.addEventListener('click', function () {
			inner.classList.add('is-expanded');
			btn.hidden = true;
		});
	});

	/* Pill tabs: sync active state with WooCommerce tab clicks */
	var spTabs = document.querySelector('.sp-tabs');
	if (spTabs) {
		var tabLinks = spTabs.querySelectorAll('.sp-tabs__pills a[role="tab"]');
		var tabPanels = spTabs.querySelectorAll('.sp-tabs__panel');

		function activateTab(targetId) {
			if (!targetId) {
				return;
			}
			tabLinks.forEach(function (link) {
				var li = link.parentElement;
				var isActive = link.getAttribute('href') === targetId;
				if (li) {
					li.classList.toggle('active', isActive);
				}
				link.setAttribute('aria-selected', isActive ? 'true' : 'false');
				link.setAttribute('tabindex', isActive ? '0' : '-1');
			});
			tabPanels.forEach(function (panel) {
				var isActive = '#' + panel.id === targetId;
				panel.classList.toggle('active', isActive);
				panel.style.display = isActive ? '' : 'none';
			});
		}

		tabLinks.forEach(function (link) {
			link.addEventListener('click', function () {
				window.setTimeout(function () {
					activateTab(link.getAttribute('href'));
				}, 10);
			});
		});

		if (typeof jQuery !== 'undefined') {
			jQuery(spTabs).on('click', '.sp-tabs__pills a', function () {
				var href = jQuery(this).attr('href');
				window.setTimeout(function () {
					activateTab(href);
				}, 15);
			});
		}

		var firstLink = tabLinks[0];
		if (firstLink) {
			activateTab(firstLink.getAttribute('href'));
		}
	}

	/* Single product: AJAX add to cart (quantity + form submit) */
	if (typeof jQuery !== 'undefined' && typeof wc_add_to_cart_params !== 'undefined') {
		jQuery(function ($) {
			var $form = $('.sp-cart-form');
			if (!$form.length) {
				return;
			}

			var $cartBtn = $form.find('.sp-btn--cart.ajax_add_to_cart');
			var $qtyInput = $form.find('.qty');
			var $buyNowFlag = $form.find('.sp-buy-now-flag');

			if (!$cartBtn.length) {
				return;
			}

			function syncQuantity() {
				var qty = parseFloat($qtyInput.val()) || 1;
				$cartBtn.attr('data-quantity', qty);
			}

			$qtyInput.on('change input', syncQuantity);
			syncQuantity();

			$(document.body).on('adding_to_cart', function (e, $button, data) {
				if (!$button || !$button.closest('.sp-cart-form').length) {
					return;
				}
				data.quantity = $qtyInput.val() || 1;
			});

			$form.on('submit', function (e) {
				if ($buyNowFlag.length && $buyNowFlag.val() === '1') {
					return;
				}
				e.preventDefault();
				$cartBtn.trigger('click');
			});
		});
	}
})();
