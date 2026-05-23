<?php
/**
 * Categories dropdown (product categories).
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
	'number'     => 12,
) );

if ( is_wp_error( $terms ) || empty( $terms ) ) {
	return;
}
?>
<div class="header-categories">
	<button type="button" class="header-categories__toggle" aria-expanded="false" aria-controls="header-categories-list">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
		<?php esc_html_e( 'CATEGORIES', 'buity-theme' ); ?>
		<svg class="header-categories__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
	</button>
	<ul id="header-categories-list" class="header-categories__list" hidden>
		<?php foreach ( $terms as $term ) : ?>
			<li>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
