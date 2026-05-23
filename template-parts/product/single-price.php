<?php
/**
 * Single product price block.
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

$on_sale    = $product->is_on_sale();
$regular    = (float) $product->get_regular_price();
$sale_price = (float) $product->get_sale_price();
$current    = $on_sale && $sale_price > 0 ? $sale_price : (float) $product->get_price();
$savings    = '';

if ( $on_sale && $regular > $current ) {
	$savings = wp_strip_all_tags( wc_price( $regular - $current ) );
}
?>
<div class="sp-price">
	<span class="sp-price__current"><?php echo wp_kses_post( wc_price( $current ) ); ?></span>
	<?php if ( $on_sale && $regular > $current ) : ?>
		<div class="sp-price__side">
			<?php if ( $savings ) : ?>
				<span class="sp-price__savings"><?php echo esc_html( $savings . ' OFF' ); ?></span>
			<?php endif; ?>
			<span class="sp-price__old"><?php echo wp_kses_post( wc_price( $regular ) ); ?></span>
		</div>
	<?php endif; ?>
</div>
