<?php
/**
 * Search results (products when post_type=product).
 *
 * @package Buity_Theme
 */

get_header();

$is_product_search = isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'];
?>

<main id="primary" class="site-main site-main--search">
	<div class="container">
		<header class="page-header">
			<h1 class="page-header__title">
				<?php
				printf(
					/* translators: %s: search query */
					esc_html__( 'Search results for: %s', 'buity-theme' ),
					'<span>' . esc_html( get_search_query() ) . '</span>'
				);
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php if ( $is_product_search && class_exists( 'WooCommerce' ) ) : ?>
				<ul class="products columns-4 buity-product-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						global $product;
						$product = wc_get_product( get_the_ID() );
						if ( $product ) {
							wc_get_template_part( 'content', 'product' );
						}
					endwhile;
					?>
				</ul>
			<?php else : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<article <?php post_class( 'entry' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</article>
				<?php endwhile; ?>
			<?php endif; ?>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No products matched your search.', 'buity-theme' ); ?></p>
			<?php get_template_part( 'template-parts/header/search-form' ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
