<?php
/**
 * Homepage product carousel section.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = get_query_var( 'buity_section', array() );
$products = get_query_var( 'buity_section_products', array() );

if ( empty( $section ) || empty( $products ) ) {
	return;
}

$section_id    = isset( $section['id'] ) ? $section['id'] : 'products';
$title         = isset( $section['title'] ) ? $section['title'] : __( 'Products', 'buity-theme' );
$view_all      = buity_section_view_all_url( $section_id );
$view_all_text = ! empty( $section['view_all_text'] ) ? $section['view_all_text'] : __( 'View All', 'buity-theme' );
?>
<section class="home-section home-products home-products--<?php echo esc_attr( $section_id ); ?>">
	<div class="container">
		<div class="home-section__header">
			<h2 class="home-section__title"><?php echo esc_html( $title ); ?></h2>
			<a class="home-section__view-all" href="<?php echo esc_url( $view_all ); ?>">
				<?php echo esc_html( $view_all_text ); ?> &rsaquo;
			</a>
		</div>
		<div class="home-products__scroll">
			<ul class="home-products__list">
				<?php
				foreach ( $products as $product ) {
					if ( ! $product instanceof WC_Product ) {
						continue;
					}
					$post_object = get_post( $product->get_id() );
					if ( ! $post_object ) {
						continue;
					}
					setup_postdata( $GLOBALS['post'] = $post_object );
					$GLOBALS['product'] = $product;
					wc_get_template_part( 'content', 'product' );
				}
				wp_reset_postdata();
				?>
			</ul>
		</div>
	</div>
</section>
