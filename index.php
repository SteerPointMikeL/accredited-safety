<?php
/**
 * Fallback template — never used directly by named pages but required by WP.
 *
 * @package ACCR_Theme
 */
get_header();
?>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h1><?php the_title(); ?></h1>
					<div><?php the_content(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<h1>Nothing here.</h1>
			<p>Try the navigation above.</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
