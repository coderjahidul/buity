<?php
/**
 * Site header — Shajghor two-tier layout.
 *
 * @package Buity_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php do_action( 'buity_before_header' ); ?>

<header class="site-header" role="banner">
	<div class="site-header__top">
		<div class="container site-header__top-inner">
			<div class="site-header__left">
				<?php get_template_part( 'template-parts/header/branding' ); ?>
				<?php get_template_part( 'template-parts/header/categories-dropdown' ); ?>
			</div>

			<div class="site-header__center">
				<?php get_template_part( 'template-parts/header/search-form' ); ?>
			</div>

			<div class="site-header__right">
				<?php get_template_part( 'template-parts/header/header-actions' ); ?>
			</div>

			<button type="button" class="site-header__toggle" id="menu-toggle" aria-expanded="false" aria-controls="primary-navigation">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'buity-theme' ); ?></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
			</button>
		</div>
	</div>

	<div class="site-header__sub">
		<div class="container">
			<?php get_template_part( 'template-parts/header/sub-navigation' ); ?>
		</div>
	</div>

	<div class="site-header__mobile-panel" id="primary-navigation">
		<?php get_template_part( 'template-parts/header/navigation' ); ?>
		<?php get_template_part( 'template-parts/header/search-form' ); ?>
	</div>
</header>
