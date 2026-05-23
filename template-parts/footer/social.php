<?php
/**
 * Footer social links.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$social = array(
	'facebook'  => array( 'label' => 'Facebook', 'url' => (string) buity_get_option( 'social_facebook' ) ),
	'instagram' => array( 'label' => 'Instagram', 'url' => (string) buity_get_option( 'social_instagram' ) ),
	'whatsapp'  => array( 'label' => 'WhatsApp', 'url' => buity_get_whatsapp_url() ),
);

$social = array_filter(
	$social,
	function ( $item ) {
		return ! empty( $item['url'] );
	}
);

if ( empty( $social ) ) {
	return;
}
?>
<div class="site-footer__col site-footer__col--social">
	<h2 class="site-footer__heading"><?php esc_html_e( 'Follow Us', 'buity-theme' ); ?></h2>
	<ul class="site-footer__social">
		<?php foreach ( $social as $item ) : ?>
			<li>
				<a href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $item['label'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
