<?php
/**
 * Product search form.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context      = get_query_var( 'buity_search_context', 'desktop' );
$is_mobile    = 'mobile' === $context;
$field_id     = $is_mobile ? 'buity-search-field-mobile' : 'buity-search-field';
$list_id      = $is_mobile ? 'buity-search-suggestions' : '';
$placeholder  = (string) buity_get_option( 'search_placeholder' );
$button_label = $is_mobile
	? (string) buity_get_option( 'mobile_search_button' )
	: __( 'Search', 'buity-theme' );
$suggestions  = $is_mobile ? buity_get_search_suggestions() : array();
$form_class   = 'product-search product-search--header' . ( $is_mobile ? ' product-search--mobile' : '' );
?>
<form role="search" method="get" class="<?php echo esc_attr( $form_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-no-results="<?php echo esc_attr( (string) buity_get_option( 'search_no_results' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $field_id ); ?>"><?php esc_html_e( 'Search products', 'buity-theme' ); ?></label>
	<input
		type="search"
		id="<?php echo esc_attr( $field_id ); ?>"
		class="product-search__input"
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
		<?php echo $list_id ? 'list="' . esc_attr( $list_id ) . '"' : ''; ?>
		autocomplete="off"
	/>
	<?php if ( $list_id && ! empty( $suggestions ) ) : ?>
		<datalist id="<?php echo esc_attr( $list_id ); ?>">
			<?php foreach ( $suggestions as $suggestion ) : ?>
				<option value="<?php echo esc_attr( $suggestion ); ?>"></option>
			<?php endforeach; ?>
		</datalist>
	<?php endif; ?>
	<input type="hidden" name="post_type" value="product" />
	<button type="submit" class="product-search__submit" aria-label="<?php echo esc_attr( $button_label ); ?>">
		<?php if ( $is_mobile && $button_label ) : ?>
			<span class="product-search__submit-text"><?php echo esc_html( $button_label ); ?></span>
		<?php else : ?>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
		<?php endif; ?>
	</button>
</form>
