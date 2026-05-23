<?php
/**
 * Header actions: wishlist, login, bag.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$account_url  = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$wishlist_url = apply_filters( 'buity_wishlist_url', $account_url );
$cart_url     = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : '#';
$cart_count   = class_exists( 'WooCommerce' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<div class="header-actions">
	<a class="header-actions__wishlist" href="<?php echo esc_url( $wishlist_url ); ?>">
		<?php esc_html_e( 'WISHLIST', 'buity-theme' ); ?>
	</a>

	<a class="header-actions__login" href="<?php echo esc_url( $account_url ); ?>">
		<?php esc_html_e( 'Login', 'buity-theme' ); ?>
	</a>

	<div class="header-cart" id="header-cart">
		<a class="header-actions__bag" href="<?php echo esc_url( $cart_url ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
				<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
				<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
			</svg>
			<span><?php esc_html_e( 'BAG', 'buity-theme' ); ?></span>
			<?php
			if ( function_exists( 'buity_cart_count_markup' ) ) {
				echo buity_cart_count_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} elseif ( $cart_count > 0 ) {
				echo '<span class="buity-cart-count buity-cart-count--has-items">' . esc_html( (string) $cart_count ) . '</span>';
			}
			?>
		</a>
		<?php get_template_part( 'template-parts/header/mini-cart' ); ?>
	</div>
</div>
