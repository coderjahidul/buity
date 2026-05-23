<?php
/**
 * Single product gallery with sale badge.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product ) {
	return;
}
?>
<div class="sp-gallery">
	<div class="sp-gallery__stage">
		<div class="sp-gallery__badge">
			<?php echo buity_get_sale_badge( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php woocommerce_show_product_images(); ?>
	</div>
</div>
