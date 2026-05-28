<?php
/**
 * Single Certification template — flexible content driven.
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
		?>
		<section class="page-hero">
			<div class="container">
				<span class="page-hero__eyebrow">Certification</span>
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
endwhile;

get_footer();
