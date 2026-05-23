<?php
/**
 * Footer contact column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone   = (string) buity_get_option( 'contact_phone' );
$email   = (string) buity_get_option( 'contact_email' );
$address = (string) buity_get_option( 'contact_address' );

if ( ! $phone && ! $email && ! $address ) {
	return;
}
?>
<div class="site-footer__col site-footer__col--contact">
	<h2 class="site-footer__heading"><?php esc_html_e( 'Contact', 'buity-theme' ); ?></h2>
	<ul class="site-footer__contact-list">
		<?php if ( $phone ) : ?>
			<li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
		<?php endif; ?>
		<?php if ( $email ) : ?>
			<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
		<?php endif; ?>
		<?php if ( $address ) : ?>
			<li><?php echo nl2br( esc_html( $address ) ); ?></li>
		<?php endif; ?>
	</ul>
</div>
