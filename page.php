<?php
/**
 * Default page template — renders the ACF flexible content for any page.
 *
 * The classic editor content (the_content) is only rendered if there are no
 * ACF page_sections AND content is non-empty. This keeps content authoring
 * 100% inside the flexible layouts while remaining safe for ad-hoc pages.
 *
 * @package ACCR_Theme
 */

get_header();

while ( have_posts() ) :
	the_post();

	$has_sections = function_exists( 'have_rows' ) && have_rows( 'page_sections' );

	if ( $has_sections ) {
		accr_render_page_sections( get_the_ID() );
	} else {
		$content = trim( get_the_content() );
		if ( $content ) {
			?>
			<section class="page-hero">
				<div class="container">
					<h1 class="page-hero__title"><?php the_title(); ?></h1>
				</div>
			</section>
			<section class="section">
				<div class="container">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		}
	}
endwhile;

get_footer();
