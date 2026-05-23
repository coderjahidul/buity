<?php
/**
 * Enqueue styles and scripts.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return filemtime for cache busting.
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function buity_asset_version( $relative_path ) {
	$file = BUITY_THEME_DIR . '/' . ltrim( $relative_path, '/' );
	return file_exists( $file ) ? (string) filemtime( $file ) : BUITY_THEME_VERSION;
}

/**
 * Enqueue theme assets.
 */
function buity_enqueue_assets() {
	wp_enqueue_style(
		'buity-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'buity-theme',
		get_stylesheet_uri(),
		array( 'buity-fonts' ),
		buity_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'buity-main',
		BUITY_THEME_URI . '/assets/css/main.css',
		array( 'buity-theme' ),
		buity_asset_version( 'assets/css/main.css' )
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'buity-home',
			BUITY_THEME_URI . '/assets/css/home.css',
			array( 'buity-main' ),
			buity_asset_version( 'assets/css/home.css' )
		);

		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style(
				'buity-shop',
				BUITY_THEME_URI . '/assets/css/shop.css',
				array( 'buity-home' ),
				buity_asset_version( 'assets/css/shop.css' )
			);
		}

		wp_enqueue_script(
			'buity-hero-slider',
			BUITY_THEME_URI . '/assets/js/hero-slider.js',
			array(),
			buity_asset_version( 'assets/js/hero-slider.js' ),
			true
		);
	}

	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_taxonomy() || is_product() || is_search() ) ) {
		wp_enqueue_style(
			'buity-shop',
			BUITY_THEME_URI . '/assets/css/shop.css',
			array( 'buity-main' ),
			buity_asset_version( 'assets/css/shop.css' )
		);
	}

	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		wp_enqueue_style(
			'buity-home',
			BUITY_THEME_URI . '/assets/css/home.css',
			array( 'buity-shop' ),
			buity_asset_version( 'assets/css/home.css' )
		);

		wp_enqueue_style(
			'buity-product',
			BUITY_THEME_URI . '/assets/css/product.css',
			array( 'buity-home' ),
			buity_asset_version( 'assets/css/product.css' )
		);

		wp_enqueue_script(
			'buity-product',
			BUITY_THEME_URI . '/assets/js/product.js',
			array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ),
			buity_asset_version( 'assets/js/product.js' ),
			true
		);
	}

	wp_enqueue_script(
		'buity-theme',
		BUITY_THEME_URI . '/assets/js/theme.js',
		array(),
		buity_asset_version( 'assets/js/theme.js' ),
		true
	);

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script(
			'buity-cart',
			BUITY_THEME_URI . '/assets/js/cart.js',
			array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ),
			buity_asset_version( 'assets/js/cart.js' ),
			true
		);

		wp_localize_script(
			'buity-cart',
			'buityCart',
			array(
				'i18n' => array(
					'success' => esc_html__( 'Success!', 'buity-theme' ),
				),
				'urls' => array(
					'cart'     => esc_url( wc_get_cart_url() ),
					'checkout' => esc_url( wc_get_checkout_url() ),
				),
			)
		);
	}

	wp_enqueue_script(
		'buity-search',
		BUITY_THEME_URI . '/assets/js/search.js',
		array(),
		buity_asset_version( 'assets/js/search.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'buity_enqueue_assets' );
