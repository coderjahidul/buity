<?php
/**
 * Single product meta links (category, brand, tags).
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

$brand = buity_get_product_brand_name( $product );
?>
<div class="sp-meta">
	<?php
	$categories = wc_get_product_category_list( $product->get_id(), ', ' );
	if ( $categories ) :
		?>
		<p class="sp-meta__row">
			<span class="sp-meta__label"><?php esc_html_e( 'Category', 'buity-theme' ); ?></span>
			<span class="sp-meta__value"><?php echo wp_kses_post( $categories ); ?></span>
		</p>
	<?php endif; ?>

	<?php if ( $brand ) : ?>
		<p class="sp-meta__row">
			<span class="sp-meta__label"><?php esc_html_e( 'Brand', 'buity-theme' ); ?></span>
			<span class="sp-meta__value sp-meta__value--text"><?php echo esc_html( $brand ); ?></span>
		</p>
	<?php endif; ?>

	<?php
	$tags = wc_get_product_tag_list( $product->get_id(), ', ' );
	if ( $tags ) :
		?>
		<p class="sp-meta__row">
			<span class="sp-meta__label"><?php esc_html_e( 'Tags', 'buity-theme' ); ?></span>
			<span class="sp-meta__value"><?php echo wp_kses_post( $tags ); ?></span>
		</p>
	<?php endif; ?>
</div>
