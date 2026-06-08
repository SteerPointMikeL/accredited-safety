<?php
/**
 * Blog index / posts archive template ("Blog-Archive" Figma frame).
 *
 * Navy hero with the blog title, followed by a responsive grid of post cards
 * (featured image, title, excerpt) and pagination. Used automatically by
 * WordPress for the Posts page; archive.php falls back here for category /
 * tag / date archives via get_template_part is unnecessary as WP routes them
 * separately, so this also doubles as the generic posts archive.
 *
 * @package ACCR_Theme
 */

get_header();

$blog_title = single_post_title( '', false );
if ( ! $blog_title ) {
	$blog_title = __( 'Industry News', 'accr-theme' );
}
?>
<section class="page-hero" aria-labelledby="page-hero-title">
	<div class="container">
		<h1 id="page-hero-title" class="page-hero__title"><?php echo esc_html( $blog_title ); ?></h1>
	</div>
</section>

<section class="section blog-archive">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="blog-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/blog/card' );
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'mid_size'           => 1,
					'class'              => 'blog-pagination',
					'screen_reader_text' => __( 'Blog posts navigation', 'accr-theme' ),
					'prev_text'          => __( 'Previous', 'accr-theme' ),
					'next_text'          => __( 'Next', 'accr-theme' ),
				)
			);
			?>
		<?php else : ?>
			<p class="blog-empty"><?php esc_html_e( 'No posts have been published yet. Please check back soon.', 'accr-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
