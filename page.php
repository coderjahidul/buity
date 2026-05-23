<?php
/**
 * Generic page template.
 *
 * @package Buity_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--page' ); ?>>
				<header class="entry__header">
					<h1 class="entry__title"><?php the_title(); ?></h1>
				</header>
				<div class="entry__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
