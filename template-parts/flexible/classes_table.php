<?php
/**
 * Layout: classes_table — auto-renders upcoming Class CPT entries as a table.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow      = get_sub_field( 'eyebrow' );
$title        = get_sub_field( 'title' );
$show_filters = get_sub_field( 'show_filters' );
$limit        = (int) get_sub_field( 'limit' );
if ( $limit < 1 ) {
	$limit = 50;
}
$only_future = get_sub_field( 'only_future' );
$footer      = get_sub_field( 'footer_button' );

$meta_query = array(
	array(
		'key'   => 'show_in_schedule',
		'value' => '1',
		'compare' => '=',
	),
);
if ( $only_future ) {
	$meta_query[] = array(
		'key'     => 'class_date',
		'value'   => date( 'Y-m-d' ),
		'compare' => '>=',
		'type'    => 'DATE',
	);
}

$q = new WP_Query(
	array(
		'post_type'      => 'class',
		'posts_per_page' => $limit,
		'orderby'        => 'meta_value',
		'meta_key'       => 'class_date',
		'order'          => 'ASC',
		'meta_query'     => $meta_query,
	)
);

// Build category filter list from terms attached to classes in this result set.
$cat_terms = array();
if ( $show_filters ) {
	$cat_terms = get_terms( array(
		'taxonomy'   => 'class_category',
		'hide_empty' => false,
	) );
	if ( is_wp_error( $cat_terms ) ) {
		$cat_terms = array();
	}
}
?>
<section id="class-schedule" class="section">
	<div class="container">
		<?php if ( $eyebrow || $title ) : ?>
			<div style="margin: 0 auto var(--space-12); text-align: center;">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2><?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_filters ) : ?>
			<div class="classes-table-filter" data-classes-filter>
				<button class="btn btn--outline is-active" data-filter="">All classes</button>
				<?php foreach ( $cat_terms as $term ) : ?>
					<button class="btn btn--outline" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="classes-table-wrap">
			<table class="classes-table">
				<thead>
					<tr>
						<th style="width: 40%;">Class</th>
						<th>Date</th>
						<th style="width: 33%;">Details</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( $q->have_posts() ) :
						while ( $q->have_posts() ) :
							$q->the_post();
							$subtitle  = get_field( 'subtitle' );
							$date_disp = accr_format_class_date_range();
							$time      = get_field( 'class_time' );
							$tuition   = accr_format_class_tuition();
							$req_label = accr_class_request_label();
							$terms     = get_the_terms( get_the_ID(), 'class_category' );
							$slugs     = array();
							if ( $terms && ! is_wp_error( $terms ) ) {
								foreach ( $terms as $t ) {
									$slugs[] = $t->slug;
								}
							}
							?>
							<tr data-class-categories="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
								<td class="class-name">
									<?php the_title(); ?>
									<?php if ( $subtitle ) : ?>
										<small><?php echo esc_html( $subtitle ); ?></small>
									<?php endif; ?>
								</td>
								<td class="class-date"><?php echo wp_kses_post( $date_disp ); ?></td>
								<td>
									<div class="class-actions">
										<button class="btn btn--primary" data-request-pricing data-class="<?php echo esc_attr( $req_label ); ?>" data-date="<?php echo esc_attr( wp_strip_all_tags( $date_disp ) ); ?>">Request pricing</button>
										<a class="btn btn--secondary" href="<?php the_permalink(); ?>"><?php _e( 'Get More Info', 'accr-theme' ); ?></a>
									</div>
								</td>
							</tr>
							<?php
						endwhile;
						wp_reset_postdata();
					else : ?>
						<tr><td colspan="5" style="text-align:center; padding: var(--space-8); color: var(--color-text-muted);">No upcoming classes scheduled. <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact us</a> for custom scheduling.</td></tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php if ( ! empty( $footer['label'] ) ) : ?>
			<div style="text-align:center; margin-top: var(--space-12);">
				<a href="<?php echo esc_url( $footer['url'] ?? '#' ); ?>" class="btn btn--secondary btn--lg">
					<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16', 'stroke-width' => '2.5' ) ); ?>
					<?php echo esc_html( $footer['label'] ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
