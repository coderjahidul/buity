<?php
/**
 * Related products — home page carousel style.
 *
 * @package Buity_Theme
 * @version 10.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $related_products ) ) {
	return;
}

if ( function_exists( 'wp_increase_content_media_count' ) ) {
	$content_media_count = wp_increase_content_media_count( 0 );
	if ( $content_media_count < wp_omit_loading_attr_threshold() ) {
		wp_increase_content_media_count( wp_omit_loading_attr_threshold() - $content_media_count );
	}
}

$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related Products', 'buity-theme' ) );
?>
<section class="home-section home-products sp-related">
	<?php if ( $heading ) : ?>
		<div class="home-section__header sp-related__header">
			<h2 class="home-section__title"><?php echo esc_html( $heading ); ?></h2>
		</div>
	<?php endif; ?>
	<div class="home-products__scroll">
		<ul class="home-products__list">
			<?php
			foreach ( $related_products as $related_product ) {
				if ( ! $related_product instanceof WC_Product ) {
					continue;
				}
				$post_object = get_post( $related_product->get_id() );
				if ( ! $post_object ) {
					continue;
				}
				setup_postdata( $GLOBALS['post'] = $post_object );
				$GLOBALS['product'] = $related_product;
				wc_get_template_part( 'content', 'product' );
			}
			wp_reset_postdata();
			?>
		</ul>
	</div>
</section>
