<?php
/**
 * Footer about column with social icons.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about = (string) buity_get_option( 'footer_description' ); 

$social = array(
	'facebook'  => (string) buity_get_option( 'social_facebook' ),
	'instagram' => (string) buity_get_option( 'social_instagram' ),
	'youtube'   => (string) buity_get_option( 'social_youtube' ),
	'whatsapp'  => buity_get_whatsapp_url(),
);
$social = array_filter( $social );
?>
<div class="site-footer__col site-footer__col--about">
	<div class="site-footer__logo">
		<?php buity_the_logo( 'footer' ); ?>
	</div>
	<?php if ( $about ) : ?>
		<p class="site-footer__about-text"><?php echo esc_html( $about ); ?></p>
	<?php endif; ?>
	<?php if ( ! empty( $social ) ) : ?>
		<p class="site-footer__share"><?php esc_html_e( 'SHARE YOUR LOVE', 'buity-theme' ); ?></p>
		<ul class="site-footer__social-icons">
			<?php if ( ! empty( $social['facebook'] ) ) : ?>
				<li><a href="<?php echo esc_url( $social['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">f</a></li>
			<?php endif; ?>
			<?php if ( ! empty( $social['instagram'] ) ) : ?>
				<li><a href="<?php echo esc_url( $social['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">in</a></li>
			<?php endif; ?>
			<?php if ( ! empty( $social['youtube'] ) ) : ?>
				<li><a href="<?php echo esc_url( $social['youtube'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube">▶</a></li>
			<?php endif; ?>
			<?php if ( ! empty( $social['whatsapp'] ) ) : ?>
				<li><a href="<?php echo esc_url( $social['whatsapp'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">W</a></li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
</div>
