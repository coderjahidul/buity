<?php
/**
 * Cart fragments and AJAX helpers.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cart count markup for fragment refresh.
 *
 * @return string
 */
function buity_cart_count_markup() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		return '<span class="buity-cart-count" aria-hidden="true">0</span>';
	}

	$count = WC()->cart->get_cart_contents_count();
	$class = 'buity-cart-count header-actions__bag-count';
	if ( $count > 0 ) {
		$class .= ' buity-cart-count--has-items';
	}

	return sprintf(
		'<span class="%s" aria-hidden="true">%d</span>',
		esc_attr( $class ),
		(int) $count
	);
}

/**
 * Add cart count to WooCommerce fragments.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function buity_cart_fragments( $fragments ) {
	$fragments['.buity-cart-count'] = buity_cart_count_markup();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'buity_cart_fragments' );
