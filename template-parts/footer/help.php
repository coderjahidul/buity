<?php
/**
 * Footer useful links column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = (string) buity_get_option( 'useful_links_heading' );
?>
<div class="site-footer__col site-footer__col--help">
	<h2 class="site-footer__heading"><?php echo esc_html( $heading ); ?></h2>
	<?php buity_footer_nav_menu( 'useful_links_menu', 'buity_footer_useful_links_fallback' ); ?>
</div>
