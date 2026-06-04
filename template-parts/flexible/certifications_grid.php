<?php
/**
 * Layout: certifications_grid — queries Certification CPT entries and renders
 * them using the same card visual pattern as the original certification cards.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow    = get_sub_field( 'eyebrow' );
$title      = get_sub_field( 'title' );
$lead       = get_sub_field( 'lead' );
$source     = get_sub_field( 'source' ) ?: 'featured';
$selected   = get_sub_field( 'selected_certifications' );
$limit      = (int) get_sub_field( 'limit' );
$cols       = get_sub_field( 'columns' ) ?: '3';
$bg         = get_sub_field( 'background' ) ?: 'default';
$footer     = get_sub_field( 'footer_button' );

$per_page = $limit > 0 ? $limit : -1;
$args     = array(
	'post_type'              => 'certification',
	'post_status'            => 'publish',
	'posts_per_page'         => $per_page,
	/* 'orderby'                => array(
		'menu_order' => 'ASC',
		'title'      => 'ASC',
		'title'      => 'ASC',
	), */
	'order'                  => 'ASC',
	'no_found_rows'          => true,
	'update_post_meta_cache' => true,
	'update_post_term_cache' => false,
);

if ( 'featured' === $source ) {
	$args['meta_query'] = array(
		array(
			'key'   => 'featured_certification',
			'value' => '1',
		),
	);
} elseif ( 'selected' === $source ) {
	$ids = array();
	if ( is_array( $selected ) ) {
		foreach ( $selected as $item ) {
			$ids[] = is_object( $item ) ? (int) $item->ID : (int) $item;
		}
	}

	$ids = array_values( array_filter( array_unique( $ids ) ) );
	if ( $ids ) {
		$args['post__in'] = $ids;
		$args['orderby']  = 'post__in';
	} else {
		$args['post__in'] = array( 0 );
	}
}

$certifications = new WP_Query( $args );
$grid_class     = 'card-grid card-grid--' . intval( $cols );

accr_section_open( array( 'background' => $bg ) );
?>
	<div class="container">
		<?php if ( $eyebrow || $title || $lead ) : ?>
			<div style="max-width: 760px; margin: 0 auto var(--space-12); text-align: center;">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2><?php endif; ?>
				<?php if ( $lead ) : ?><p class="section-lead"><?php echo wp_kses_post( $lead ); ?></p><?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $certifications->have_posts() ) : ?>
			<div class="<?php echo esc_attr( $grid_class ); ?>">
				<?php
				while ( $certifications->have_posts() ) :
					$certifications->the_post();
					$cert_id    = get_the_ID();
					$image      = get_field( 'card_image', $cert_id );
					$short_name = get_field( 'short_name', $cert_id );
					$heading    = $short_name ? $short_name : get_the_title();
					$summary    = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 28 );
					$anchor     = get_post_field( 'post_name', $cert_id );
					$button     = get_field( 'button', $cert_id );
					?>
					<article id="<?php echo esc_attr( $anchor ); ?>" class="cert-card">
						<?php if ( is_array( $image ) && ! empty( $image['url'] ) ) : ?>
							<div class="cert-card__media">
								<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" />
								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/cert-card__badge.webp" alt="CCO" width="75" height=="75" class="cert-card__badge" />
							</div>
						<?php endif; ?>
						<div class="cert-card__body">
							<h3 class="cert-card__title"><?php echo esc_html( $heading ); ?></h3>
							<?php if ( $summary ) : ?>
								<p class="cert-card__text"><?php echo wp_kses_post( $summary ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $button['url'] ) ) : ?>
								<a class="btn btn--primary" href="<?php echo esc_attr( $button['url'] ?: '#' ); ?>" style="align-self:flex-start; padding-left:var(--space-4);">
									<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16', 'stroke-width' => '2.5' ) ); ?>
									<?php echo esc_html( $button['label'] ?: __( 'Get Details', 'accr-theme' ) ); ?>
								</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>

		<?php if ( ! empty( $footer['label'] ) ) : ?>
			<div style="text-align:center; margin-top: var(--space-12);">
				<a href="<?php echo esc_url( $footer['url'] ?? '#' ); ?>" class="btn btn--secondary btn--lg">
					<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16', 'stroke-width' => '2.5' ) ); ?>
					<?php echo esc_html( $footer['label'] ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
<?php
accr_section_close();
