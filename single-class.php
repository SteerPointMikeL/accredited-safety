<?php
/**
 * Single Class template — shows class details + flexible sections (if any).
 *
 * @package ACCR_Theme
 */
get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	$subtitle  = function_exists( 'get_field' ) ? get_field( 'subtitle', $post_id ) : '';
	$date      = accr_format_class_date( $post_id );
	$time      = function_exists( 'get_field' ) ? get_field( 'class_time', $post_id ) : '';
	$tuition   = accr_format_class_tuition( $post_id );
	$req_label = accr_class_request_label( $post_id );
	?>
	<section class="page-hero">
		<div class="container">
			<span class="page-hero__eyebrow">Class</span>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="page-hero__lead"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<div class="card-grid card-grid--3" style="margin-bottom: var(--space-10);">
				<?php if ( $date ) : ?>
					<div class="feature-card">
						<div class="feature-card__icon"><?php echo accr_icon( 'calendar' ); ?></div>
						<h3>Date</h3>
						<p><?php echo wp_kses_post( $date ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( $time ) : ?>
					<div class="feature-card">
						<div class="feature-card__icon"><?php echo accr_icon( 'clock' ); ?></div>
						<h3>Time</h3>
						<p><?php echo esc_html( $time ); ?></p>
					</div>
				<?php endif; ?>
				<div class="feature-card">
					<div class="feature-card__icon"><?php echo accr_icon( 'award' ); ?></div>
					<h3>Tuition</h3>
					<p><?php echo wp_kses_post( $tuition ); ?></p>
					<div style="margin-top: var(--space-5);">
						<button class="btn btn--primary" data-request-pricing data-class="<?php echo esc_attr( $req_label ); ?>" data-date="<?php echo esc_attr( wp_strip_all_tags( $date ) ); ?>">Request pricing</button>
					</div>
				</div>
			</div>

			<?php
			$has_sections = function_exists( 'have_rows' ) && have_rows( 'page_sections', $post_id );
			if ( ! $has_sections ) {
				the_content();
			}
			?>
		</div>
	</section>

	<?php
	if ( $has_sections ) {
		accr_render_page_sections( $post_id );
	}
endwhile;

get_footer();
