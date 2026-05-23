<?php
/**
 * Single product tabs — pill style Description + Reviews.
 *
 * @package Buity_Theme
 * @version 9.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( empty( $product_tabs ) ) {
	return;
}

$tab_keys = array_keys( $product_tabs );
?>
<div class="woocommerce-tabs wc-tabs-wrapper sp-tabs">
	<ul class="tabs wc-tabs sp-tabs__pills" role="tablist">
		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<?php
			$is_active = ( $key === $tab_keys[0] );
			$tab_title = apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key );
			?>
			<li role="presentation" class="<?php echo esc_attr( $key ); ?>_tab<?php echo $is_active ? ' active' : ''; ?>" id="tab-title-<?php echo esc_attr( $key ); ?>">
				<a href="#tab-<?php echo esc_attr( $key ); ?>" role="tab" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<?php echo esc_html( wp_strip_all_tags( $tab_title ) ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<div class="sp-tabs__body">
		<?php
		$tab_index = 0;
		foreach ( $product_tabs as $key => $product_tab ) :
			$is_first = ( 0 === $tab_index );
			++$tab_index;
			$is_description = ( 'description' === $key );
			?>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab sp-tabs__panel<?php echo $is_first ? ' active' : ''; ?>" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<div class="sp-tabs__panel-scroll<?php echo $is_description ? ' sp-tabs__panel-scroll--clamp' : ''; ?>"<?php echo $is_description ? ' data-sp-tab-panel' : ''; ?>>
					<?php
					if ( isset( $product_tab['callback'] ) ) {
						call_user_func( $product_tab['callback'], $key, $product_tab );
					}
					?>
				</div>
				<?php if ( $is_description ) : ?>
					<button type="button" class="sp-tabs__see-more" data-sp-see-more hidden>
						<?php esc_html_e( 'See More', 'buity-theme' ); ?>
					</button>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php do_action( 'woocommerce_product_after_tabs' ); ?>
</div>
