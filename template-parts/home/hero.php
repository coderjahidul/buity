<?php
/**
 * Homepage hero carousel.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slides = array_values(
	array_filter(
		buity_get_hero_slides(),
		function ( $slide ) {
			$image_id = (int) ( $slide['image_id'] ?? 0 );
			$has_text = ! empty( $slide['title_left'] ) || ! empty( $slide['subtitle_left'] ) || ! empty( $slide['title_right'] );
			return $image_id || $has_text;
		}
	)
);

if ( empty( $slides ) ) {
	return;
}
?>
<section class="home-hero-slider" aria-label="<?php esc_attr_e( 'Featured promotions', 'buity-theme' ); ?>">
	<div class="home-hero-slider__track">
		<?php foreach ( $slides as $index => $slide ) : ?>
			<?php
			$image_id = (int) ( $slide['image_id'] ?? 0 );
			$has_text = ! empty( $slide['title_left'] ) || ! empty( $slide['subtitle_left'] ) || ! empty( $slide['title_right'] );
			$link      = ! empty( $slide['link'] ) ? $slide['link'] : '';
			$active    = 0 === $index ? ' is-active' : '';
			$slide_alt = ! empty( $slide['alt'] ) ? $slide['alt'] : __( 'Promotional slide', 'buity-theme' );
			?>
			<div class="home-hero-slider__slide<?php echo esc_attr( $active ); ?><?php echo $has_text ? ' home-hero-slider__slide--has-text' : ''; ?>" data-slide="<?php echo esc_attr( (string) $index ); ?>" role="group" aria-label="<?php echo esc_attr( $slide_alt ); ?>">
				<?php if ( $image_id ) : ?>
					<figure class="home-hero-slider__media">
						<?php
						echo wp_get_attachment_image(
							$image_id,
							'full',
							false,
							array(
								'class'    => 'home-hero-slider__image',
								'alt'      => $slide_alt,
								'loading'  => 0 === $index ? 'eager' : 'lazy',
								'decoding' => 'async',
								'sizes'    => '100vw',
							)
						);
						?>
					</figure>
				<?php endif; ?>

				<?php if ( $link ) : ?>
					<a class="home-hero-slider__slide-link" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $slide_alt ); ?>"></a>
				<?php endif; ?>

				<?php if ( $has_text ) : ?>
					<div class="container home-hero-slider__content">
						<div class="home-hero-slider__left">
							<?php if ( ! empty( $slide['title_left'] ) ) : ?>
								<h2 class="home-hero-slider__title-left"><?php echo esc_html( $slide['title_left'] ); ?></h2>
							<?php endif; ?>
							<?php if ( ! empty( $slide['subtitle_left'] ) ) : ?>
								<p class="home-hero-slider__subtitle-left"><?php echo esc_html( $slide['subtitle_left'] ); ?></p>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $slide['title_right'] ) ) : ?>
							<div class="home-hero-slider__right">
								<p class="home-hero-slider__title-right"><?php echo esc_html( $slide['title_right'] ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $slides ) > 1 ) : ?>
		<div class="home-hero-slider__dots" role="tablist">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<button
					type="button"
					class="home-hero-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
					data-slide-to="<?php echo esc_attr( (string) $index ); ?>"
					role="tab"
					aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
					aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'buity-theme' ), $index + 1 ) ); ?>"
				></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
