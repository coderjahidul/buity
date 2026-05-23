<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and menus.
 */
function buity_theme_setup() {
	load_theme_textdomain( 'buity-theme', BUITY_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 240,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'buity-theme' ),
		'footer'  => esc_html__( 'Footer Menu', 'buity-theme' ),
	) );

	add_image_size( 'buity-product-card', 400, 400, true );
	add_image_size( 'buity-hero', 1600, 600, true );

	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 600,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 4,
			),
		) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'buity_theme_setup' );

/**
 * Fallback primary menu when none assigned.
 */
function buity_primary_menu_fallback() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		echo '<ul class="primary-menu"><li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'buity-theme' ) . '</a></li></ul>';
		return;
	}
	?>
	<ul class="primary-menu">
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'buity-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'buity-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My Account', 'buity-theme' ); ?></a></li>
	</ul>
	<?php
}

/**
 * Fallback footer menu.
 */
function buity_footer_menu_fallback() {
	$links = array();
	if ( class_exists( 'WooCommerce' ) ) {
		$links = array(
			array( wc_get_page_permalink( 'shop' ), __( 'Shop', 'buity-theme' ) ),
			array( wc_get_page_permalink( 'myaccount' ), __( 'My Account', 'buity-theme' ) ),
			array( wc_get_cart_url(), __( 'Cart', 'buity-theme' ) ),
		);
	}
	if ( empty( $links ) ) {
		$links[] = array( home_url( '/' ), __( 'Home', 'buity-theme' ) );
	}
	?>
	<nav class="footer-nav">
		<ul class="footer-menu">
			<?php foreach ( $links as $link ) : ?>
				<li><a href="<?php echo esc_url( $link[0] ); ?>"><?php echo esc_html( $link[1] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Footer legal menu fallback.
 */
function buity_footer_legal_fallback() {
	?>
	<ul class="site-footer__legal-menu">
		<li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'buity-theme' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'buity-theme' ); ?></a></li>
	</ul>
	<?php
}

/**
 * Footer quick links fallback.
 */
function buity_footer_quick_links_fallback() {
	$links = array(
		array( home_url( '/return-refund-policy/' ), __( 'Return & Refund Policy', 'buity-theme' ) ),
		array( home_url( '/privacy-policy/' ), __( 'Privacy Policy', 'buity-theme' ) ),
		array( home_url( '/terms-and-conditions/' ), __( 'Terms and Conditions', 'buity-theme' ) ),
		array( home_url( '/about-us/' ), __( 'About Us', 'buity-theme' ) ),
	);
	?>
	<ul class="site-footer__links">
		<?php foreach ( $links as $link ) : ?>
			<li><a href="<?php echo esc_url( $link[0] ); ?>"><?php echo esc_html( $link[1] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
}
