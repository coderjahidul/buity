<?php
/**
 * Site footer — Shajghor navy layout.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="site-footer" role="contentinfo">
	<div class="container site-footer__grid">
		<?php get_template_part( 'template-parts/footer/about' ); ?>
		<?php get_template_part( 'template-parts/footer/top-categories' ); ?>
		<?php get_template_part( 'template-parts/footer/quick-links' ); ?>
		<?php get_template_part( 'template-parts/footer/help' ); ?>
		<?php get_template_part( 'template-parts/footer/payments' ); ?>
	</div>
	<div class="site-footer__legal">
		<div class="container site-footer__legal-inner">
			<nav class="site-footer__legal-nav" aria-label="<?php esc_attr_e( 'Legal', 'buity-theme' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'menu_class'     => 'site-footer__legal-menu',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'buity_footer_legal_fallback',
				) );
				?>
			</nav>
			<p class="site-footer__copyright">
				<?php echo wp_kses_post( buity_get_copyright_html() ); ?>
			</p>
		</div>
	</div>
</footer>

<?php if ( class_exists( 'WooCommerce' ) ) : ?>
<div id="buity-cart-notice" class="buity-cart-notice" role="status" aria-live="polite" hidden>
	<div class="buity-cart-notice__inner">
		<div class="buity-cart-notice__main">
			<span class="buity-cart-notice__icon" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M8 12l3 3 5-6"/></svg>
			</span>
			<div class="buity-cart-notice__text">
				<strong class="buity-cart-notice__title"><?php esc_html_e( 'Success!', 'buity-theme' ); ?></strong>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="buity-cart-notice__view-cart"><?php esc_html_e( 'View Cart', 'buity-theme' ); ?></a>
			</div>
		</div>
		<div class="buity-cart-notice__actions">
			<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="buity-cart-notice__buy-now">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
				<?php esc_html_e( 'Buy Now', 'buity-theme' ); ?>
			</a>
			<button type="button" class="buity-cart-notice__close" aria-label="<?php esc_attr_e( 'Close', 'buity-theme' ); ?>">
				<?php esc_html_e( 'Close', 'buity-theme' ); ?>
			</button>
		</div>
	</div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
