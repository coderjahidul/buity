<?php
/**
 * Front page template — Shajghor layout.
 *
 * @package Buity_Theme
 */

get_header();
?>

<main id="primary" class="site-main site-main--home">
	<?php get_template_part( 'template-parts/home/hero' ); ?>
	<?php get_template_part( 'template-parts/home/categories-grid' ); ?>

	<?php
	$sections = buity_get_home_sections();
	foreach ( $sections as $section ) {
		$products = buity_get_section_products( $section['args'] );
		if ( empty( $products ) ) {
			continue;
		}
		set_query_var( 'buity_section', $section );
		set_query_var( 'buity_section_products', $products );
		get_template_part( 'template-parts/home/product-section' );
	}
	?>
</main>

<?php
get_footer();
