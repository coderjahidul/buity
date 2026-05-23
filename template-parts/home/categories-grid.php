<?php
/**
 * Homepage product categories — pink square tiles.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) || ! taxonomy_exists( 'product_cat' ) ) {
	return;
}

$terms = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => true,
	'parent'     => 0,
	'number'     => 4,
) );

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}

$view_all = buity_section_view_all_url( 'categories' );
?>
<section class="home-section home-categories">
	<div class="container">
		<div class="home-section__header">
			<h2 class="home-section__title"><?php esc_html_e( 'PRODUCTS BY CATEGORY', 'buity-theme' ); ?></h2>
			<a class="home-section__view-all" href="<?php echo esc_url( $view_all ); ?>">
				<?php esc_html_e( 'View All', 'buity-theme' ); ?> &rsaquo;
			</a>
		</div>
		<ul class="home-categories__grid">
			<?php foreach ( $terms as $term ) : ?>
				<?php
				$link       = get_term_link( $term );
				$thumb_id   = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
				$thumb_html = $thumb_id
					? wp_get_attachment_image( $thumb_id, 'medium', false, array( 'class' => 'home-categories__img' ) )
					: '';
				?>
				<li class="home-categories__item">
					<a class="home-categories__link" href="<?php echo esc_url( $link ); ?>">
						<?php if ( $thumb_html ) : ?>
							<?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<span class="home-categories__placeholder" aria-hidden="true"></span>
						<?php endif; ?>
						<span class="home-categories__name"><?php echo esc_html( $term->name ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
