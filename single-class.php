<?php
/**
 * Single Class template — Individual-Class layout (hero, pricing band, schedule
 * panel, two-column detail blocks). Renders from the Class details fields with
 * safe fallbacks; optional flexible page sections (e.g. the travel CTA) render
 * below the panel.
 *
 * @package ACCR_Theme
 */
get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	$gf = function_exists( 'get_field' );

	/* ----- Hero ----- */
	$hero_title = $gf ? get_field( 'hero_title', $post_id ) : '';
	if ( ! $hero_title ) {
		$hero_title = get_the_title( $post_id );
	}
	$hero_image = $gf ? get_field( 'hero_image', $post_id ) : '';
	$hero_intro = $gf ? get_field( 'hero_intro', $post_id ) : '';
	if ( ! $hero_intro ) {
		$excerpt    = get_the_excerpt( $post_id );
		$hero_intro = $excerpt ? wpautop( $excerpt ) : '';
	}
	// Optional foreground image still overlays the shared dots background.
	$hero_src = '';
	if ( is_array( $hero_image ) && ! empty( $hero_image['url'] ) ) {
		$hero_src = $hero_image['url'];
	} elseif ( has_post_thumbnail( $post_id ) ) {
		$hero_src = get_the_post_thumbnail_url( $post_id, 'accr-hero' );
	}

	/* ----- Pricing band ----- */
	$pricing_label = $gf ? get_field( 'pricing_label', $post_id ) : '';
	$pricing_note  = $gf ? get_field( 'pricing_note', $post_id ) : '';
	if ( ! $pricing_label ) {
		$pricing_label = __( 'Pricing available on request.', 'accr-theme' );
	}
	if ( ! $pricing_note ) {
		$pricing_note = __( 'Tuition varies by class size, location, and employer-group discounts — we reply within one business day.', 'accr-theme' );
	}

	/* ----- Class info / schedule panel ----- */
	$info_heading = $gf ? get_field( 'info_heading', $post_id ) : '';
	if ( ! $info_heading ) {
		$info_heading = get_the_title( $post_id );
	}
	$date     = accr_format_class_date_range( $post_id );
	$time     = $gf ? get_field( 'class_time', $post_id ) : '';
	$tuition  = accr_format_class_tuition( $post_id );
	$req_label = accr_class_request_label( $post_id );

	// Featured image shown beside the panel heading, if present.
	$panel_image_id = has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : 0;

	$schedule_rows = $gf ? get_field( 'schedule_rows', $post_id ) : array();
	if ( empty( $schedule_rows ) ) {
		// Fallback: build a single row from the date / time fields.
		$fallback_line = trim( wp_strip_all_tags( $date ) . ( $time ? ' · ' . $time : '' ) );
		if ( $fallback_line ) {
			$schedule_rows = array(
				array(
					'badge'       => __( 'Session', 'accr-theme' ),
					'datetime'    => $fallback_line,
					'description' => '',
				),
			);
		}
	}

	/* ----- Lower two-column detail blocks ----- */
	// Preferred: the reusable detail_columns repeater (icon + heading + content).
	// Fallback: the legacy single-purpose fields, preserved for existing content.
	$info_blocks = array();
	$detail_rows = $gf ? get_field( 'detail_columns', $post_id ) : array();
	if ( ! empty( $detail_rows ) && is_array( $detail_rows ) ) {
		foreach ( $detail_rows as $row ) {
			$content = isset( $row['content'] ) ? $row['content'] : '';
			if ( ! $content ) {
				continue;
			}
			$info_blocks[] = array(
				'icon'    => ! empty( $row['icon'] ) ? $row['icon'] : 'check_circle',
				'heading' => isset( $row['heading'] ) ? $row['heading'] : '',
				'content' => $content,
			);
		}
	}

	if ( empty( $info_blocks ) ) {
		$topics         = $gf ? get_field( 'topics_covered', $post_id ) : '';
		$designations   = $gf ? get_field( 'designations', $post_id ) : '';
		$what_to_bring  = $gf ? get_field( 'what_to_bring', $post_id ) : '';
		$accommodations = $gf ? get_field( 'accommodations', $post_id ) : '';

		if ( ! $designations ) {
			// Fallback: derive designations from class_category terms.
			$terms = get_the_terms( $post_id, 'class_category' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$items = '';
				foreach ( $terms as $t ) {
					$items .= '<li>' . esc_html( $t->name ) . '</li>';
				}
				$designations = '<ul>' . $items . '</ul>';
			}
		}

		$info_blocks = array(
			array(
				'icon'    => 'layers',
				'heading' => __( 'Topics Covered', 'accr-theme' ),
				'content' => $topics,
			),
			array(
				'icon'    => 'award',
				'heading' => __( 'Designations', 'accr-theme' ),
				'content' => $designations,
			),
			array(
				'icon'    => 'check_circle',
				'heading' => __( 'What to Bring', 'accr-theme' ),
				'content' => $what_to_bring,
			),
			array(
				'icon'    => 'users',
				'heading' => __( 'Accommodations', 'accr-theme' ),
				'content' => $accommodations,
			),
		);
		$info_blocks = array_values( array_filter( $info_blocks, function ( $b ) {
			return ! empty( $b['content'] );
		} ) );
	}

	/* ----- Call button (phone) ----- */
	$phone_display = get_theme_mod( 'accr_phone_display', '844-484-9628' );
	$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8444849628' );
	?>

	<section class="class-hero<?php echo $hero_src ? ' class-hero--has-image' : ''; ?>">
		<?php if ( $hero_src ) : ?>
			<img class="class-hero__image" src="<?php echo esc_url( $hero_src ); ?>" alt="" aria-hidden="true" />
		<?php endif; ?>
		<div class="class-hero__overlay" aria-hidden="true"></div>
		<div class="container class-hero__content">
			<h1 class="class-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
			<?php if ( $hero_intro ) : ?>
				<div class="class-hero__intro"><?php echo wp_kses_post( $hero_intro ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="class-pricing-band">
		<div class="container class-pricing-band__inner">
			<?php echo accr_icon( 'alert', array( 'width' => '22', 'height' => '22' ) ); ?>
			<p class="class-pricing-band__label"><?php echo esc_html( $pricing_label ); ?></p>
			<p class="class-pricing-band__note"><?php echo esc_html( $pricing_note ); ?></p>
		</div>
	</section>

	<section class="section class-detail">
		<div class="container">
			<div class="class-panel">
				<div class="class-panel__title-block<?php echo $panel_image_id ? ' class-panel__title-block--has-image' : ''; ?>">
					<?php if ( $panel_image_id ) : ?>
						<div class="class-panel__image">
							<?php echo wp_get_attachment_image( $panel_image_id, 'accr-card', false, array( 'class' => 'class-panel__image-img', 'alt' => '' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="class-panel__title-text">
						<h2 class="class-panel__heading"><?php echo esc_html( $info_heading ); ?></h2>
						<?php if ( $date ) : ?>
							<p class="class-panel__date"><?php echo wp_kses_post( $date ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $schedule_rows ) ) : ?>
					<ul class="class-schedule" role="list">
						<?php
						$i = 0;
						foreach ( $schedule_rows as $row ) :
							$i++;
							$badge = isset( $row['badge'] ) ? $row['badge'] : '';
							$dt    = isset( $row['datetime'] ) ? $row['datetime'] : '';
							$rtime = isset( $row['time'] ) ? $row['time'] : '';
							$desc  = isset( $row['description'] ) ? $row['description'] : '';
							// Compose the date line, appending the optional time field.
							if ( $rtime ) {
								$dt = $dt ? $dt . ' · ' . $rtime : $rtime;
							}
							$badge_mod = ( $i % 2 === 1 ) ? 'class-schedule__badge--orange' : 'class-schedule__badge--navy';
							?>
							<li class="class-schedule__row">
								<?php if ( $badge ) : ?>
									<span class="class-schedule__badge <?php echo esc_attr( $badge_mod ); ?>"><?php echo esc_html( $badge ); ?></span>
								<?php endif; ?>
								<div class="class-schedule__body">
									<?php if ( $dt ) : ?>
										<p class="class-schedule__datetime"><?php echo esc_html( $dt ); ?></p>
									<?php endif; ?>
									<?php if ( $desc ) : ?>
										<p class="class-schedule__desc"><?php echo esc_html( $desc ); ?></p>
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="class-panel__tuition">
					<span class="class-panel__tuition-label"><?php esc_html_e( 'Tuition', 'accr-theme' ); ?></span>
					<span class="class-panel__tuition-value"><?php echo wp_kses_post( $tuition ); ?></span>
				</div>

				<?php if ( ! empty( $info_blocks ) ) : ?>
					<div class="class-info-grid">
						<?php foreach ( $info_blocks as $block ) : ?>
							<div class="class-info-block">
								<h3 class="class-info-block__heading">
									<?php echo accr_icon( $block['icon'], array( 'width' => '20', 'height' => '20' ) ); ?>
									<?php echo esc_html( $block['heading'] ); ?>
								</h3>
								<div class="class-info-block__body"><?php echo wp_kses_post( $block['content'] ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="class-panel__actions">
					<button class="btn btn--primary btn--lg btn--block" data-request-pricing data-class="<?php echo esc_attr( $req_label ); ?>" data-date="<?php echo esc_attr( wp_strip_all_tags( $date ) ); ?>">
						<?php esc_html_e( 'Request Pricing', 'accr-theme' ); ?>
					</button>
					<a class="btn btn--secondary btn--lg btn--block" href="<?php echo esc_attr( $phone_link ); ?>">
						<?php echo accr_icon( 'phone' ); ?>
						<?php echo esc_html( sprintf( __( 'Call: %s', 'accr-theme' ), $phone_display ) ); ?>
					</a>
				</div>
			</div>

			<?php
			$has_sections = function_exists( 'have_rows' ) && have_rows( 'page_sections', $post_id );
			if ( ! $has_sections ) {
				$content = get_the_content();
				if ( trim( wp_strip_all_tags( $content ) ) ) {
					echo '<div class="class-content rich-content">';
					the_content();
					echo '</div>';
				}
			}
			?>
		</div>
	</section>

	<?php
	// Optional flexible page sections render below the class panel. The
	// "We Will Travel to You" CTA is authored here via the travel_cta layout
	// instead of being hardcoded into this template.
	if ( $has_sections ) {
		accr_render_page_sections( $post_id );
	}
endwhile;

get_footer();
