<?php
/**
 * Footer quick links column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = (string) buity_get_option( 'quick_links_heading' );
?>
<div class="site-footer__col site-footer__col--links">
	<h2 class="site-footer__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php buity_footer_nav_menu( 'quick_links_menu', 'buity_footer_quick_links_fallback' ); ?>
</div>
