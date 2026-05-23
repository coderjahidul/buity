<?php
/**
 * Top Brands promotional banner grid.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banners  = buity_get_brand_banners();
$view_all = buity_section_view_all_url( 'brands' );

if ( empty( $banners ) ) {
	return;
}
?>
<section class="home-section home-brands">
	<div class="container">
		<div class="home-section__header">
			<h2 class="home-section__title"><?php esc_html_e( 'TOP BRANDS', 'buity-theme' ); ?></h2>
			<a class="home-section__view-all" href="<?php echo esc_url( $view_all ); ?>">
				<?php esc_html_e( 'View All', 'buity-theme' ); ?> &rsaquo;
			</a>
		</div>
		<div class="home-brands__grid">
			<?php foreach ( $banners as $banner ) : ?>
				<?php
				$span_class = 'home-brands__item--' . ( ! empty( $banner['span'] ) ? sanitize_html_class( $banner['span'] ) : 'normal' );
				$link       = ! empty( $banner['link'] ) ? $banner['link'] : '#';
				$style      = ! empty( $banner['color'] ) ? 'background-color:' . esc_attr( $banner['color'] ) . ';' : '';
				?>
				<a class="home-brands__item <?php echo esc_attr( $span_class ); ?>" href="<?php echo esc_url( $link ); ?>" style="<?php echo esc_attr( $style ); ?>">
					<?php if ( ! empty( $banner['image_id'] ) ) : ?>
						<?php echo wp_get_attachment_image( $banner['image_id'], 'medium_large', false, array( 'class' => 'home-brands__img' ) ); ?>
					<?php elseif ( ! empty( $banner['label'] ) ) : ?>
						<span class="home-brands__label"><?php echo esc_html( $banner['label'] ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
