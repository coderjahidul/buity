<?php
/**
 * Secondary header navigation.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$track_url = get_theme_mod( 'buity_track_order_url', '' );
if ( ! $track_url && class_exists( 'WooCommerce' ) ) {
	$track_url = wc_get_account_endpoint_url( 'orders' );
}
?>
<nav class="sub-nav" aria-label="<?php esc_attr_e( 'Secondary', 'buity-theme' ); ?>">
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'sub-nav__menu',
			'container'      => false,
			'fallback_cb'    => false,
		) );
	} else {
		?>
		<ul class="sub-nav__menu">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'buity-theme' ); ?></a></li>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Products', 'buity-theme' ); ?></a></li>
			<?php endif; ?>
			<?php if ( $track_url ) : ?>
				<li><a href="<?php echo esc_url( $track_url ); ?>"><?php esc_html_e( 'Track Order', 'buity-theme' ); ?></a></li>
			<?php endif; ?>
		</ul>
		<?php
	}
	?>
</nav>
