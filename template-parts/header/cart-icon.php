<?php
/**
 * Cart icon with count badge.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
	return;
}

$cart_url = wc_get_cart_url();
?>
<a class="site-header__cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'buity-theme' ); ?>">
	<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
		<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
		<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
	</svg>
	<?php echo buity_cart_count_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</a>
