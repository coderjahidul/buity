<?php
/**
 * Footer quick links column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="site-footer__col site-footer__col--links">
	<h2 class="site-footer__heading"><?php esc_html_e( 'Quick Links', 'buity-theme' ); ?></h2>
	<?php
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'menu_class'     => 'site-footer__links',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => 'buity_footer_quick_links_fallback',
	) );
	?>
</div>
