<?php
/**
 * Generic archive template (category, tag, date, author) for blog posts.
 *
 * Shares the navy hero + post-card grid used by the blog index so taxonomy
 * archives stay visually consistent with "Industry News". Custom Post Type
 * archives keep WordPress' own routing (archive-{cpt}.php) and are unaffected.
 *
 * @package ACCR_Theme
 */

get_header();

$archive_title = get_the_archive_title();
?>
<section class="blog-hero" aria-labelledby="blog-hero-title">
	<div class="container">
		<h1 id="blog-hero-title" class="blog-hero__title"><?php echo wp_kses_post( $archive_title ); ?></h1>
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
			<p class="blog-empty"><?php esc_html_e( 'No posts found.', 'accr-theme' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
