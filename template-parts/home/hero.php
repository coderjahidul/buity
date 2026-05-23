<?php
/**
 * Homepage hero carousel.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$slides = buity_get_hero_slides();
if ( empty( $slides ) ) {
	return;
}
?>
<section class="home-hero-slider" aria-label="<?php esc_attr_e( 'Featured promotions', 'buity-theme' ); ?>">
	<div class="home-hero-slider__track">
		<?php foreach ( $slides as $index => $slide ) : ?>
			<?php
			$bg_style = 'background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);';
			if ( ! empty( $slide['image_id'] ) ) {
				$url = wp_get_attachment_image_url( $slide['image_id'], 'buity-hero' );
				if ( $url ) {
					$bg_style = sprintf(
						'background-image: linear-gradient(rgba(0,0,0,.45), rgba(0,0,0,.45)), url(%s); background-size: cover; background-position: center;',
						esc_url( $url )
					);
				}
			}
			$link = ! empty( $slide['link'] ) ? $slide['link'] : '';
			$active = 0 === $index ? ' is-active' : '';
			?>
			<div class="home-hero-slider__slide<?php echo esc_attr( $active ); ?>" data-slide="<?php echo esc_attr( (string) $index ); ?>" style="<?php echo esc_attr( $bg_style ); ?>">
				<?php if ( $link ) : ?><a class="home-hero-slider__slide-link" href="<?php echo esc_url( $link ); ?>"><?php endif; ?>
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
				<?php if ( $link ) : ?></a><?php endif; ?>
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
