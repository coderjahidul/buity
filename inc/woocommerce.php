<?php
/**
 * WooCommerce theme integration.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	return 12;
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
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'buity_related_products_args' );

/**
 * Open main content wrapper.
 */
function buity_woocommerce_wrapper_before() {
	echo '<main id="primary" class="site-main site-main--shop"><div class="container">';
}
add_action( 'woocommerce_before_main_content', 'buity_woocommerce_wrapper_before', 5 );

/**
 * Close main content wrapper.
 */
function buity_woocommerce_wrapper_after() {
	echo '</div></main>';
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
