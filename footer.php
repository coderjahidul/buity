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

<div id="buity-toast" class="buity-toast" role="status" aria-live="polite" hidden></div>

<?php wp_footer(); ?>
</body>
</html>
