<?php
/**
 * Single blog post template ("Blog-Single" Figma frame).
 *
 * Pale-gray intro band with the navy post title and meta on the left and the
 * featured image on the right (stacks on mobile), followed by the article body.
 *
 * Only applies to the standard `post` type — Custom Post Types keep their own
 * single-{cpt}.php templates.
 *
 * @package ACCR_Theme
 */

// CPTs ship their own single-{cpt}.php templates. For any post type that is
// not a standard blog post and lacks a dedicated template, defer to the
// generic fallback so CPT single views remain unaffected by the blog design.
if ( ! is_singular( 'post' ) ) {
	get_template_part( 'index' );
	return;
}

get_header();

while ( have_posts() ) :
	the_post();
	$has_thumb = has_post_thumbnail();
	?>
	<article <?php post_class( 'blog-single' ); ?>>
		<section class="blog-single__intro<?php echo $has_thumb ? '' : ' blog-single__intro--no-media'; ?>">
			<div class="container blog-single__intro-inner">
				<div class="blog-single__intro-text">
					<p class="blog-single__eyebrow">
						<?php
						$primary_cat = get_the_category();
						if ( ! empty( $primary_cat ) ) {
							echo esc_html( $primary_cat[0]->name );
						} else {
							esc_html_e( 'Industry News', 'accr-theme' );
						}
						?>
					</p>
					<h1 class="blog-single__title"><?php the_title(); ?></h1>
					<p class="blog-single__meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</p>
				</div>
				<?php if ( $has_thumb ) : ?>
					<div class="blog-single__media">
						<?php the_post_thumbnail( 'accr-hero', array( 'class' => 'blog-single__img' ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

		<section class="section blog-single__content-section">
			<div class="container">
				<div class="blog-single__content">
					<?php the_content(); ?>
				</div>

				<?php
				wp_link_pages(
					array(
						'before'      => '<nav class="blog-single__pages" aria-label="' . esc_attr__( 'Post pages', 'accr-theme' ) . '"><span>' . esc_html__( 'Pages:', 'accr-theme' ) . '</span>',
						'after'       => '</nav>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					)
				);
				?>
			</div>
		</section>
	</article>
<?php endwhile; ?>
<?php
get_footer();
