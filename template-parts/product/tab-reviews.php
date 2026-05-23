<?php
/**
 * Reviews tab — rating summary + breakdown (Shajghor mockup).
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

$average       = (float) $product->get_average_rating();
$review_count  = (int) $product->get_review_count();
$rating_counts = $product->get_rating_counts();
$display_score = $review_count > 0 ? round( $average, 1 ) : 0;
$total_reviews = max( 0, $review_count );
?>
<div class="sp-reviews-summary">
	<div class="sp-reviews-summary__overall">
		<div class="sp-reviews-summary__score">
			<?php echo esc_html( (string) $display_score ); ?><span class="sp-reviews-summary__score-max">/5</span>
		</div>
		<div class="sp-reviews-summary__stars" aria-hidden="true">
			<?php echo buity_render_review_stars( $average, $review_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<p class="sp-reviews-summary__count">
			<?php
			printf(
				/* translators: %d: number of reviews */
				esc_html( _n( '(%d Review)', '(%d Reviews)', $review_count, 'buity-theme' ) ),
				$review_count
			);
			?>
		</p>
	</div>

	<div class="sp-reviews-summary__bars">
		<?php for ( $star = 5; $star >= 1; $star-- ) : ?>
			<?php
			$star_count = 0;
			if ( isset( $rating_counts[ $star ] ) ) {
				$star_count = (int) $rating_counts[ $star ];
			} elseif ( isset( $rating_counts[ (string) $star ] ) ) {
				$star_count = (int) $rating_counts[ (string) $star ];
			}
			$percent = $total_reviews > 0 ? ( $star_count / $total_reviews ) * 100 : 0;
			?>
			<div class="sp-reviews-bar">
				<span class="sp-reviews-bar__stars" aria-hidden="true">
					<?php echo buity_render_review_stars_row( $star ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<span class="sp-reviews-bar__track">
					<span class="sp-reviews-bar__fill" style="width: <?php echo esc_attr( (string) $percent ); ?>%;"></span>
				</span>
				<span class="sp-reviews-bar__num">(<?php echo esc_html( (string) $star_count ); ?>)</span>
			</div>
		<?php endfor; ?>
	</div>
</div>

