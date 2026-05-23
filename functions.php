<?php
/**
 * Buity Theme bootstrap.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BUITY_THEME_VERSION', '1.0.0' );
define( 'BUITY_THEME_DIR', get_template_directory() );
define( 'BUITY_THEME_URI', get_template_directory_uri() );

$buity_includes = array(
	'theme-setup.php',
	'theme-options.php',
	'enqueue.php',
	'woocommerce.php',
	'home-helpers.php',
	'cart-ajax.php',
	'customizer.php',
);

foreach ( $buity_includes as $file ) {
	$path = BUITY_THEME_DIR . '/inc/' . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
