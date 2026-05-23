<?php
/**
 * Main template fallback.
 *
 * @package Buity_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
					<header class="entry__header">
						<h1 class="entry__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h1>
						<p class="entry__meta"><?php echo esc_html( get_the_date() ); ?></p>
					</header>
					<div class="entry__content">
						<?php the_excerpt(); ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No posts found.', 'buity-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
