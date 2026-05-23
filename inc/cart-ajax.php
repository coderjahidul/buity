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
 * Mini-cart dropdown HTML for header and AJAX fragments.
 *
 * @return string
 */
function buity_get_mini_cart_html() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return '';
	}

	ob_start();
	get_template_part( 'template-parts/header/mini-cart' );
	return ob_get_clean();
}

/**
 * Add cart count to WooCommerce fragments.
 *
 * @param array $fragments Cart fragments.
 * @return array
 */
function buity_cart_fragments( $fragments ) {
	$fragments['.buity-cart-count']  = buity_cart_count_markup();
	$fragments['#buity-mini-cart']   = buity_get_mini_cart_html();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'buity_cart_fragments' );

/**
 * Hide default WooCommerce “added to cart” notice (theme uses buity-cart-notice).
 *
 * @return string
 */
function buity_disable_add_to_cart_notice() {
	return '';
}
add_filter( 'wc_add_to_cart_message_html', 'buity_disable_add_to_cart_notice' );

/**
 * Clear add-to-cart notices after AJAX add (prevents duplicate messages).
 *
 * @param int $product_id Product ID.
 */
function buity_clear_ajax_add_to_cart_notices( $product_id ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
	wc_clear_notices();
}
add_action( 'woocommerce_ajax_added_to_cart', 'buity_clear_ajax_add_to_cart_notices', 1 );

/**
 * Whether AJAX add to cart is available for a product.
 *
 * @param WC_Product|null $product Product.
 * @return bool
 */
function buity_ajax_add_to_cart_enabled( $product = null ) {
	if ( 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) {
		return false;
	}

	if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
		return false;
	}

	if ( $product instanceof WC_Product ) {
		if ( ! $product->supports( 'ajax_add_to_cart' ) ) {
			return false;
		}

		if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return false;
		}
	}

	return true;
}

/**
 * Ensure loop add-to-cart links include AJAX classes and data attributes.
 *
 * @param array      $args    Button args.
 * @param WC_Product $product Product.
 * @return array
 */
function buity_loop_add_to_cart_args( $args, $product ) {
	if ( ! ( $product instanceof WC_Product ) ) {
		return $args;
	}

	$classes = isset( $args['class'] ) ? (string) $args['class'] : 'button';
	$type    = 'product_type_' . $product->get_type();

	if ( false === strpos( $classes, $type ) ) {
		$classes .= ' ' . $type;
	}

	$theme_btn = wc_wp_theme_get_element_class_name( 'button' );
	if ( $theme_btn && false === strpos( $classes, $theme_btn ) ) {
		$classes .= ' ' . $theme_btn;
	}

	if ( $product->is_purchasable() && $product->is_in_stock() ) {
		if ( false === strpos( $classes, 'add_to_cart_button' ) ) {
			$classes .= ' add_to_cart_button';
		}

		if ( buity_ajax_add_to_cart_enabled( $product ) && false === strpos( $classes, 'ajax_add_to_cart' ) ) {
			$classes .= ' ajax_add_to_cart';
		}
	}

	$args['class'] = trim( $classes );

	if ( ! isset( $args['attributes'] ) || ! is_array( $args['attributes'] ) ) {
		$args['attributes'] = array();
	}

	if ( empty( $args['attributes']['data-product_id'] ) ) {
		$args['attributes']['data-product_id'] = (string) $product->get_id();
	}

	if ( empty( $args['attributes']['data-product_sku'] ) && $product->get_sku() ) {
		$args['attributes']['data-product_sku'] = $product->get_sku();
	}

	if ( buity_ajax_add_to_cart_enabled( $product ) && $product instanceof WC_Product_Simple && empty( $args['attributes']['data-success_message'] ) ) {
		$args['attributes']['data-success_message'] = wp_strip_all_tags( $product->add_to_cart_success_message() );
	}

	return $args;
}
add_filter( 'woocommerce_loop_add_to_cart_args', 'buity_loop_add_to_cart_args', 20, 2 );

/**
 * Load WooCommerce AJAX cart scripts on all storefront pages.
 */
function buity_enqueue_ajax_add_to_cart_scripts() {
	if ( ! class_exists( 'WooCommerce' ) || is_admin() ) {
		return;
	}

	wp_enqueue_script( 'wc-add-to-cart' );
	wp_enqueue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'buity_enqueue_ajax_add_to_cart_scripts', 99 );
