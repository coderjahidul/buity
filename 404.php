<?php
/**
 * 404 template.
 *
 * @package Buity_Theme
 */

get_header();
?>

<main id="primary" class="site-main site-main--404">
	<div class="container">
		<section class="error-404">
			<h1 class="error-404__title"><?php esc_html_e( 'Page not found', 'buity-theme' ); ?></h1>
			<p class="error-404__text"><?php esc_html_e( 'Sorry, we could not find what you were looking for.', 'buity-theme' ); ?></p>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<a class="btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Browse the shop', 'buity-theme' ); ?>
				</a>
			<?php else : ?>
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Go home', 'buity-theme' ); ?>
				</a>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php
get_footer();
