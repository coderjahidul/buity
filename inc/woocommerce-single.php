<?php
/**
 * Single product page customizations.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register single-product hooks.
 */
function buity_single_product_hooks() {
	if ( ! is_product() ) {
		return;
	}

	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

	add_action( 'woocommerce_before_single_product_summary', 'buity_single_product_gallery', 20 );
	add_action( 'woocommerce_single_product_summary', 'buity_single_product_summary', 5 );
}
add_action( 'wp', 'buity_single_product_hooks' );

/**
 * Configure product tabs (Description + Reviews pills).
 *
 * @param array<string, array<string, mixed>> $tabs Product tabs.
 * @return array<string, array<string, mixed>>
 */
function buity_configure_product_tabs( $tabs ) {
	unset( $tabs['additional_information'] );

	global $product;

	if ( ! isset( $tabs['description'] ) ) {
		$tabs['description'] = array(
			'title'    => __( 'Description', 'buity-theme' ),
			'priority' => 10,
			'callback' => 'buity_product_description_tab',
		);
	} else {
		$tabs['description']['title']    = __( 'Description', 'buity-theme' );
		$tabs['description']['callback'] = 'buity_product_description_tab';
	}

	$count = ( $product instanceof WC_Product ) ? (int) $product->get_review_count() : 0;

	if ( ! isset( $tabs['reviews'] ) ) {
		$tabs['reviews'] = array(
			'title'    => sprintf( __( 'Reviews(%d)', 'buity-theme' ), $count ),
			'priority' => 30,
			'callback' => 'buity_product_reviews_tab',
		);
	} else {
		$tabs['reviews']['title']    = sprintf( __( 'Reviews(%d)', 'buity-theme' ), $count );
		$tabs['reviews']['callback'] = 'buity_product_reviews_tab';
	}

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'buity_configure_product_tabs', 98 );

/**
 * Description tab with extra product meta.
 */
function buity_product_description_tab() {
	wc_get_template( 'single-product/tabs/description.php' );
}

/**
 * Reviews tab — summary layout + WooCommerce comments.
 */
function buity_product_reviews_tab() {
	get_template_part( 'template-parts/product/tab-reviews' );
}

/**
 * Star row for review breakdown (1–5 filled stars).
 *
 * @param int $filled Stars filled (1-5).
 * @return string
 */
function buity_render_review_stars_row( $filled ) {
	$html = '';
	for ( $i = 1; $i <= 5; $i++ ) {
		$html .= '<span class="sp-review-star' . ( $i <= $filled ? ' is-filled' : '' ) . '">★</span>';
	}
	return $html;
}

/**
 * Stars from average rating (summary column).
 *
 * @param float $average       Average rating.
 * @param int   $review_count  Total reviews.
 * @return string
 */
function buity_render_review_stars( $average, $review_count = 0 ) {
	$html = '';
	for ( $i = 1; $i <= 5; $i++ ) {
		$filled = $review_count > 0 && $average >= ( $i - 0.25 );
		$html  .= '<span class="sp-review-star' . ( $filled ? ' is-filled' : '' ) . '">★</span>';
	}
	return $html;
}

/**
 * Product gallery with sale badge.
 */
function buity_single_product_gallery() {
	get_template_part( 'template-parts/product/single-gallery' );
}

/**
 * Full product summary (title, price, cart, meta).
 */
function buity_single_product_summary() {
	get_template_part( 'template-parts/product/single-summary' );
}

/**
 * Categories strip below related products.
 */
function buity_single_product_categories_strip() {
	get_template_part( 'template-parts/product/categories-strip' );
}

/**
 * Add to cart button label.
 *
 * @return string
 */
function buity_single_add_to_cart_text() {
	return __( 'Add to cart', 'buity-theme' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'buity_single_add_to_cart_text' );

/**
 * Redirect to checkout after Buy Now.
 *
 * @param string $url Redirect URL.
 * @return string
 */
function buity_buy_now_redirect( $url ) {
	if ( isset( $_REQUEST['buity-buy-now'] ) && '1' === (string) wp_unslash( $_REQUEST['buity-buy-now'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return wc_get_checkout_url();
	}
	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'buity_buy_now_redirect' );

/**
 * Get display brand name for a product.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function buity_get_product_brand_name( $product ) {
	if ( ! $product ) {
		return '';
	}

	$taxonomies = array( 'product_brand', 'pwb-brand' );
	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		$terms = get_the_terms( $product->get_id(), $taxonomy );
		if ( $terms && ! is_wp_error( $terms ) ) {
			return $terms[0]->name;
		}
	}

	$brand = $product->get_attribute( 'brand' );
	if ( $brand ) {
		return $brand;
	}

	$brand = $product->get_attribute( 'pa_brand' );
	return $brand ? $brand : '';
}

/**
 * Savings amount label for on-sale products.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function buity_get_product_savings_label( $product ) {
	if ( ! $product || ! $product->is_on_sale() ) {
		return '';
	}

	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();

	if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
		return '';
	}

	$savings = $regular - $sale;
	$amount  = wp_strip_all_tags( wc_price( $savings ) );

	return sprintf(
		/* translators: %s: formatted savings amount */
		__( '-%s OFF', 'buity-theme' ),
		$amount
	);
}
