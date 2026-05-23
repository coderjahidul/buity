<?php
/**
 * Product card — Shajghor style.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product || ! $product->is_visible() ) {
	return;
}

$permalink = $product->get_permalink();
$image_id  = $product->get_image_id();
?>
<li <?php wc_product_class( 'product-card', $product ); ?>>
	<div class="product-card__top">
		<?php echo buity_get_sale_badge( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<a href="<?php echo esc_url( apply_filters( 'buity_wishlist_url', $permalink ) ); ?>" class="product-card__wishlist" aria-label="<?php esc_attr_e( 'Add to wishlist', 'buity-theme' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
		</a>
	</div>

	<a href="<?php echo esc_url( $permalink ); ?>" class="product-card__image-link">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image( $image_id, 'buity-product-card', false, array( 'class' => 'product-card__image' ) );
		} else {
			echo wc_placeholder_img( 'buity-product-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</a>

	<div class="product-card__body">
		<h2 class="product-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
		</h2>

		<?php echo buity_product_star_rating( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<div class="product-card__price">
			<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<div class="product-card__actions">
			<?php
			woocommerce_template_loop_add_to_cart(
				array(
					'class' => 'product-card__cart-btn',
				)
			);
			?>
		</div>
	</div>
</li>
