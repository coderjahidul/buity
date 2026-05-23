<?php
/**
 * Footer top categories column.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms = array();
if ( taxonomy_exists( 'product_cat' ) ) {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => 6,
	) );
}
?>
<div class="site-footer__col site-footer__col--categories">
	<h2 class="site-footer__heading"><?php esc_html_e( 'Top Categories', 'buity-theme' ); ?></h2>
	<?php if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) : ?>
		<ul class="site-footer__links">
			<?php foreach ( $terms as $term ) : ?>
				<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
