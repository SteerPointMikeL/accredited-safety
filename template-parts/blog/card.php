<?php
/**
 * Blog post card — used inside the blog archive grid.
 *
 * Renders the featured image (with a graceful fallback when none is set),
 * the post title and excerpt. The whole card is a single accessible link
 * via a stretched-link overlay so any part of the card is clickable while
 * the heading remains the accessible name.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$permalink = get_permalink();
$excerpt   = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 );
?>
<article <?php post_class( 'blog-card' ); ?>>
	<a class="blog-card__media" href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'accr-card', array( 'class' => 'blog-card__img', 'loading' => 'lazy', 'alt' => '' ) ); ?>
		<?php else : ?>
			<img class="blog-card__img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/page-hero.webp' ); ?>" alt="" width="800" height="600" loading="lazy" />
		<?php endif; ?>
	</a>
	<div class="blog-card__body">
		<h2 class="blog-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
		</h2>
		<?php if ( $excerpt ) : ?>
			<p class="blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<?php endif; ?>
	</div>
</article>
