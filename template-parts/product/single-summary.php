<?php
/**
 * Single product summary — matches Shajghor mockup.
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

$review_count = (int) $product->get_review_count();
?>
<div class="sp-summary">
	<h1 class="sp-summary__title"><?php the_title(); ?></h1>

	<?php if ( wc_review_ratings_enabled() ) : ?>
		<div class="sp-summary__rating">
			<?php echo buity_product_star_rating( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="sp-summary__review-count">
				<?php
				printf(
					/* translators: %d: number of reviews */
					esc_html( _n( '(%d review)', '(%d reviews)', $review_count, 'buity-theme' ) ),
					$review_count
				);
				?>
			</span>
		</div>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/product/single-price' ); ?>

	<div class="sp-summary__purchase">
		<?php woocommerce_template_single_add_to_cart(); ?>
	</div>

	<?php get_template_part( 'template-parts/product/single-meta' ); ?>
</div>
