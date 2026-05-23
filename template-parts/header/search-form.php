<?php
/**
 * Product search form.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="product-search product-search--header" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="buity-search-field"><?php esc_html_e( 'Search products', 'buity-theme' ); ?></label>
	<input
		type="search"
		id="buity-search-field"
		class="product-search__input"
		placeholder="<?php esc_attr_e( 'Search products…', 'buity-theme' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	/>
	<input type="hidden" name="post_type" value="product" />
	<button type="submit" class="product-search__submit" aria-label="<?php esc_attr_e( 'Search', 'buity-theme' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
	</button>
</form>
