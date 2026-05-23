<?php
/**
 * Single product content — Shajghor layout.
 *
 * @package Buity_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

if ( ! $product ) {
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sp-product', $product ); ?>>
	<div class="container sp-product__container">
		<div class="sp-product__grid">
			<div class="sp-product__gallery-col">
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
			</div>
			<div class="sp-product__summary-col">
				<div class="sp-product__summary">
					<?php do_action( 'woocommerce_single_product_summary' ); ?>
				</div>
			</div>
		</div>

		<div class="sp-product__below">
			<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
			<?php buity_single_product_categories_strip(); ?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
