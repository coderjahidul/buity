<?php
/**
 * SKU, categories, tags, and brand below description tab content.
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

$sku    = $product->get_sku();
$brand  = buity_get_product_brand_name( $product );
$tags   = wc_get_product_tag_list( $product->get_id(), ', ' );
$cats   = wc_get_product_category_list( $product->get_id(), ', ' );

if ( ! $sku && ! $brand && ! $tags && ! $cats ) {
	return;
}
?>
<div class="sp-tab-meta">
	<?php if ( $sku ) : ?>
		<p class="sp-tab-meta__row">
			<strong class="sp-tab-meta__label"><?php esc_html_e( 'SKU', 'buity-theme' ); ?></strong>
			<span class="sp-tab-meta__value"><?php echo esc_html( $sku ); ?></span>
		</p>
	<?php endif; ?>

	<?php if ( $cats ) : ?>
		<p class="sp-tab-meta__row">
			<strong class="sp-tab-meta__label"><?php esc_html_e( 'Categories', 'buity-theme' ); ?></strong>
			<span class="sp-tab-meta__value"><?php echo wp_kses_post( $cats ); ?></span>
		</p>
	<?php endif; ?>

	<?php if ( $tags ) : ?>
		<p class="sp-tab-meta__row">
			<strong class="sp-tab-meta__label"><?php esc_html_e( 'Tags', 'buity-theme' ); ?></strong>
			<span class="sp-tab-meta__value"><?php echo wp_kses_post( $tags ); ?></span>
		</p>
	<?php endif; ?>

	<?php if ( $brand ) : ?>
		<p class="sp-tab-meta__row">
			<strong class="sp-tab-meta__label"><?php esc_html_e( 'Brands', 'buity-theme' ); ?></strong>
			<span class="sp-tab-meta__value sp-tab-meta__value--text"><?php echo esc_html( $brand ); ?></span>
		</p>
	<?php endif; ?>
</div>
