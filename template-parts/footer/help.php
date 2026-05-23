<?php
/**
 * Footer help column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$help_links = array(
	array( get_theme_mod( 'buity_help_faq_url', '' ), __( 'FAQ', 'buity-theme' ) ),
	array( get_theme_mod( 'buity_help_support_url', '' ), __( 'Support', 'buity-theme' ) ),
	array( get_theme_mod( 'buity_help_contact_url', '' ), __( 'Contact Us', 'buity-theme' ) ),
);
$help_links = array_filter( $help_links, function ( $item ) {
	return ! empty( $item[0] );
} );
?>
<div class="site-footer__col site-footer__col--help">
	<h2 class="site-footer__heading"><?php esc_html_e( 'Help', 'buity-theme' ); ?></h2>
	<?php if ( ! empty( $help_links ) ) : ?>
		<ul class="site-footer__links">
			<?php foreach ( $help_links as $link ) : ?>
				<li><a href="<?php echo esc_url( $link[0] ); ?>"><?php echo esc_html( $link[1] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<ul class="site-footer__links">
			<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'buity-theme' ); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Support', 'buity-theme' ); ?></a></li>
		</ul>
	<?php endif; ?>
</div>
