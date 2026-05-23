<?php
/**
 * Simple product add to cart — Shajghor layout.
 *
 * @package Buity_Theme
 * @version 10.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

if ( ! $product->is_in_stock() ) {
	return;
}

$min_qty = $product->get_min_purchase_quantity();

$ajax_enabled = buity_ajax_add_to_cart_enabled( $product );

$ajax_btn_classes = $ajax_enabled ? ' ajax_add_to_cart add_to_cart_button' : '';
$ajax_btn_attrs   = '';

if ( $ajax_enabled ) {
	$ajax_btn_attrs = sprintf(
		' data-product_id="%1$d" data-product_sku="%2$s" data-quantity="%3$s"',
		(int) $product->get_id(),
		esc_attr( $product->get_sku() ),
		esc_attr( (string) $min_qty )
	);

	if ( $product instanceof WC_Product_Simple ) {
		$ajax_btn_attrs .= sprintf(
			' data-success_message="%s"',
			esc_attr( wp_strip_all_tags( $product->add_to_cart_success_message() ) )
		);
	}
}

do_action( 'woocommerce_before_add_to_cart_form' );
?>
<form class="cart sp-cart-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<div class="sp-cart-form__actions">
		<div class="sp-cart-form__qty">
			<?php
			do_action( 'woocommerce_before_add_to_cart_quantity' );

			$min = $min_qty;
			$max = $product->get_max_purchase_quantity();
			$val = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $min; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			woocommerce_quantity_input(
				array(
					'min_value'   => $min,
					'max_value'   => $max,
					'input_value' => $val,
					'classes'     => array( 'sp-qty__input', 'qty' ),
				)
			);

			do_action( 'woocommerce_after_add_to_cart_quantity' );
			?>
		</div>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="sp-btn sp-btn--cart single_add_to_cart_button button alt<?php echo esc_attr( $ajax_btn_classes . ( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) ); ?>"<?php echo $ajax_btn_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
			<?php esc_html_e( 'Add to cart', 'buity-theme' ); ?>
		</button>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="sp-btn sp-btn--buy-now" data-buity-buy-now="1">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			<?php esc_html_e( 'Buy Now', 'buity-theme' ); ?>
		</button>

		<input type="hidden" name="buity-buy-now" value="0" class="sp-buy-now-flag" />
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
</form>
<?php
do_action( 'woocommerce_after_add_to_cart_form' );
