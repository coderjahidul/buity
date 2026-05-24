<?php
/**
 * WooCommerce theme integration.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once BUITY_THEME_DIR . '/inc/woocommerce-single.php';

/**
 * WooCommerce setup after theme setup.
 */
function buity_woocommerce_setup() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'after_setup_theme', 'buity_woocommerce_setup', 20 );

/**
 * Products per page.
 *
 * @return int
 */
function buity_products_per_page() {
	$per_page = function_exists( 'buity_get_option' )
		? (int) buity_get_option( 'shop_products_per_page', 12 )
		: 12;
	return max( 1, min( 100, $per_page ) );
}
add_filter( 'loop_shop_per_page', 'buity_products_per_page' );

/**
 * Shop columns.
 *
 * @return int
 */
function buity_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'buity_loop_columns' );

/**
 * Related products count.
 *
 * @param array $args Related products args.
 * @return array
 */
function buity_related_products_args( $args ) {
	$args['posts_per_page'] = 10;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'buity_related_products_args' );

/**
 * Open main content wrapper.
 */
function buity_woocommerce_wrapper_before() {
	$extra = is_checkout() ? ' site-main--checkout' : '';
	echo '<main id="primary" class="site-main site-main--shop' . $extra . '">';
	if ( ! is_product() && ! is_checkout() ) {
		echo '<div class="container">';
	}
}
add_action( 'woocommerce_before_main_content', 'buity_woocommerce_wrapper_before', 5 );

/**
 * Close main content wrapper.
 */
function buity_woocommerce_wrapper_after() {
	if ( ! is_product() && ! is_checkout() ) {
		echo '</div>';
	}
	echo '</main>';
}
add_action( 'woocommerce_after_main_content', 'buity_woocommerce_wrapper_after', 50 );

/**
 * Remove default WooCommerce wrappers (we use our own).
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Product search: limit to products.
 *
 * @param WP_Query $query Main query.
 */
function buity_product_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	if ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) {
		$query->set( 'post_type', 'product' );
	}
}
add_action( 'pre_get_posts', 'buity_product_search_query' );

/**
 * Sale badge HTML.
 *
 * @param WC_Product $product Product object.
 * @return string
 */
function buity_get_sale_badge( $product ) {
	if ( ! $product || ! $product->is_on_sale() ) {
		return '';
	}

	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();
	$label   = esc_html__( 'Sale', 'buity-theme' );

	if ( $regular > 0 && $sale > 0 ) {
		$percent = round( ( ( $regular - $sale ) / $regular ) * 100 );
		$label   = sprintf( '%d%% OFF', $percent );
	}

	return '<span class="product-card__badge product-card__badge--off">' . esc_html( $label ) . '</span>';
}

/**
 * AJAX: update cart item quantity from the custom checkout page.
 */
function buity_bco_update_cart_item() {
	check_ajax_referer( 'bco_cart_update', 'nonce' );

	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	$qty = isset( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 0;

	if ( ! $key ) {
		wp_send_json_error( array( 'message' => 'Invalid cart item key.' ) );
	}

	$cart = WC()->cart;

	if ( 0 === $qty ) {
		$cart->remove_cart_item( $key );
	} else {
		$cart->set_quantity( $key, $qty, true );
	}

	$cart->calculate_totals();

	wp_send_json_success(
		array(
			'subtotal'    => $cart->get_cart_subtotal(),
			'discount'    => wc_price( $cart->get_discount_total() ),
			'shipping'    => wc_price( $cart->get_shipping_total() ),
			'grand_total' => $cart->get_total(),
			'count'       => $cart->get_cart_contents_count(),
		)
	);
}
add_action( 'wp_ajax_bco_update_cart_item',        'buity_bco_update_cart_item' );
add_action( 'wp_ajax_nopriv_bco_update_cart_item', 'buity_bco_update_cart_item' );

/**
 * Body class for custom checkout page.
 *
 * @param array $classes Body classes.
 * @return array
 */
function buity_bco_body_class( $classes ) {
	if ( is_checkout() && ! is_wc_endpoint_url() ) {
		$classes[] = 'buity-custom-checkout';
	}
	return $classes;
}
add_filter( 'body_class', 'buity_bco_body_class' );
