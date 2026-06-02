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

	/* ----- Hero ----- */
	$hero_title = get_field( 'hero_title' );
	if ( ! $hero_title ) {
		$hero_title = get_the_title();
	}
	$hero_intro = get_field( 'hero_intro' );
	if ( ! $hero_intro ) {
		$excerpt    = get_the_excerpt();
		$hero_intro = $excerpt ? wpautop( $excerpt ) : '';
	}

	/* ----- Pricing band ----- */
	$pricing_label = get_field( 'pricing_label' );
	$pricing_note  = get_field( 'pricing_note' );
	if ( ! $pricing_label ) {
		$pricing_label = __( 'Pricing available on request.', 'accr-theme' );
	}
	if ( ! $pricing_note ) {
		$pricing_note = __( '<p>Tuition varies by class size, location, and employer-group discounts — we reply within one business day.</p>', 'accr-theme' );
	}

	/* ----- Class info / schedule panel ----- */
	$info_heading = get_field( 'info_heading' );
	if ( ! $info_heading ) {
		$info_heading = get_the_title();
	}
	$date      = accr_format_class_date_range();
	$location  = get_field( 'location' );
	$req_label = get_field( 'request_class_label' ) ?: get_the_title();

	// Featured image shown beside the panel heading, if present.
	$panel_image_id = has_post_thumbnail() ? get_post_thumbnail_id() : 0;

	$schedule_rows = get_field( 'schedule_rows' );

	/* ----- Lower two-column detail blocks ----- */
	// Preferred: the reusable detail_columns repeater (icon + heading + content).
	// Fallback: the legacy single-purpose fields, preserved for existing content.
	$info_blocks = array();
	$detail_rows = get_field( 'detail_columns' );
	if ( ! empty( $detail_rows ) && is_array( $detail_rows ) ) {
		foreach ( $detail_rows as $row ) {
			$content = isset( $row['content'] ) ? $row['content'] : '';
			if ( ! $content ) {
				continue;
			}
			$info_blocks[] = array(
				'icon'         => ! empty( $row['icon'] ) ? $row['icon'] : 'check_circle',
				'heading'      => isset( $row['heading'] ) ? $row['heading'] : '',
				'content'      => $content,
			);
		}
	}

	/* ----- Call button (phone) ----- */
	$phone_display = get_theme_mod( 'accr_phone_display', '844-484-9628' );
	$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8444849628' );
	?>

	<section class="page-hero">
		<div class="container">
			<?php if ( $hero_title ) : ?>
				<h1 class="page-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
			<?php endif; ?>
			<?php if ( $hero_intro ) : ?>
				<div class="page-hero__lead">
					<?php echo wp_kses_post( $hero_intro ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="notice-bar notice-bar-warning">
		<div class="container">
			<p class="notice-bar__title">
				<?php echo accr_icon( 'alert', array( 'width' => '22', 'height' => '22', 'fill' => 'currentColor', 'stroke' => 'none' ) ); ?>
				<?php echo esc_html( $pricing_label ); ?>
			</p>
			<?php echo $pricing_note; ?>
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
							<p class="class-panel__date"><?php echo $date; ?></p>
						<?php endif; ?>
						<?php if ( $location ) : ?>
							<p class="class-panel__location"><?php echo wp_kses_post( $location ); ?></p>
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
								$dt = $dt ? $dt . ' &bull; ' . $rtime : $rtime;
							}
							?>
							<li class="class-schedule__row">
								<?php if ( $badge ) : ?>
									<span class="class-schedule__badge"><?php echo esc_html( $badge ); ?></span>
								<?php endif; ?>
								<div class="class-schedule__body">
									<?php if ( $dt ) : ?>
										<span class="class-schedule__datetime"><?php echo esc_html( $dt ); ?></span>
									<?php endif; ?>
									<?php if ( $desc ) : ?>
										<span class="class-schedule__desc"><?php echo esc_html( $desc ); ?></span>
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( ! empty( $info_blocks ) ) : ?>
					<div class="class-info-grid">
						<?php foreach ( $info_blocks as $block ) : ?>
							<div class="class-info-block">
								<h3 class="class-info-block__heading">
									<?php echo accr_icon( $block['icon'], array( 'viewBox' => '0 0 20 20', 'fill' => 'none', 'stroke' => 'none' ) ); ?>
									<?php echo esc_html( $block['heading'] ); ?>
								</h3>
								<div class="class-info-block__body"><?php echo wp_kses_post( $block['content'] ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="class-panel__actions">
					<button class="btn btn--primary btn--lg" data-request-pricing data-class="<?php echo esc_attr( $req_label ); ?>" data-date="<?php echo esc_attr( wp_strip_all_tags( $date ) ); ?>">
						<?php esc_html_e( 'Request Pricing', 'accr-theme' ); ?>
					</button>
					<a class="btn btn--secondary btn--lg" href="<?php echo esc_attr( $phone_link ); ?>">
						<?php echo esc_html( sprintf( __( 'Call: %s', 'accr-theme' ), $phone_display ) ); ?>
					</a>
				</div>
			</div>

			<?php
			$has_sections = function_exists( 'have_rows' ) && have_rows( 'page_sections' );
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
		accr_render_page_sections( get_the_ID() );
	}
endwhile;

get_footer();
