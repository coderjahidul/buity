<?php
/**
 * Header mini-cart dropdown panel.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
	return;
}

$cart = WC()->cart;
?>
<div class="buity-mini-cart" id="buity-mini-cart">
	<div class="buity-mini-cart__panel" role="region" aria-label="<?php esc_attr_e( 'Shopping bag', 'buity-theme' ); ?>">
		<?php if ( $cart->is_empty() ) : ?>
			<p class="buity-mini-cart__empty"><?php esc_html_e( 'Your bag is empty.', 'buity-theme' ); ?></p>
		<?php else : ?>
			<ul class="buity-mini-cart__items">
				<?php
				foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
						continue;
					}

					if ( ! apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						continue;
					}

					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					$thumbnail_id      = $_product->get_image_id();
					?>
					<li class="buity-mini-cart__item woocommerce-mini-cart-item">
						<?php if ( $product_permalink ) : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>" class="buity-mini-cart__thumb">
								<?php
								if ( $thumbnail_id ) {
									echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail', false, array( 'class' => 'buity-mini-cart__img' ) );
								} else {
									echo wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'buity-mini-cart__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</a>
							<a href="<?php echo esc_url( $product_permalink ); ?>" class="buity-mini-cart__title">
								<?php echo esc_html( $product_name ); ?>
							</a>
						<?php else : ?>
							<span class="buity-mini-cart__thumb">
								<?php
								if ( $thumbnail_id ) {
									echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail', false, array( 'class' => 'buity-mini-cart__img' ) );
								} else {
									echo wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'buity-mini-cart__img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</span>
							<span class="buity-mini-cart__title"><?php echo esc_html( $product_name ); ?></span>
						<?php endif; ?>

						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="buity-mini-cart__remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_attr(
									sprintf(
										/* translators: %s: product name */
										__( 'Remove %s from cart', 'buity-theme' ),
										wp_strip_all_tags( $product_name )
									)
								),
								esc_attr( $product_id ),
								esc_attr( $cart_item_key ),
								esc_attr( $_product->get_sku() )
							),
							$cart_item_key
						);
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<div class="buity-mini-cart__subtotal">
				<span class="buity-mini-cart__subtotal-label"><?php esc_html_e( 'Sub Total', 'buity-theme' ); ?></span>
				<span class="buity-mini-cart__subtotal-price"><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></span>
			</div>

			<div class="buity-mini-cart__buttons">
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="buity-mini-cart__btn buity-mini-cart__btn--view">
					<?php esc_html_e( 'View Cart', 'buity-theme' ); ?>
				</a>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="buity-mini-cart__btn buity-mini-cart__btn--checkout">
					<?php esc_html_e( 'Checkout', 'buity-theme' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
