<?php
/**
 * Primary navigation.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'buity-theme' ); ?>">
	<?php
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'menu_class'     => 'primary-menu',
		'container'      => false,
		'fallback_cb'    => 'buity_primary_menu_fallback',
	) );
	?>
</nav>
