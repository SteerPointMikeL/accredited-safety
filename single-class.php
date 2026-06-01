<?php
/**
 * Single Class template — Individual-Class layout (hero, pricing band, schedule
 * panel, info blocks, CTA). Renders entirely from the Class details fields with
 * safe fallbacks; optional flexible sections render below.
 *
 * @package ACCR_Theme
 */
get_header();

while ( have_posts() ) :
	the_post();
	$post_id = get_the_ID();

	$gf = function_exists( 'get_field' );

	/* ----- Hero ----- */
	$subtitle   = $gf ? get_field( 'subtitle', $post_id ) : '';
	$hero_image = $gf ? get_field( 'hero_image', $post_id ) : '';
	$hero_intro = $gf ? get_field( 'hero_intro', $post_id ) : '';
	if ( ! $hero_intro ) {
		$excerpt    = get_the_excerpt( $post_id );
		$hero_intro = $excerpt ? wpautop( $excerpt ) : '';
	}
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
	$date     = accr_format_class_date( $post_id );
	$time     = $gf ? get_field( 'class_time', $post_id ) : '';
	$tuition  = accr_format_class_tuition( $post_id );
	$req_label = accr_class_request_label( $post_id );

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

	/* ----- Lower info blocks ----- */
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

	/* ----- Call button (phone) ----- */
	$phone_display = get_theme_mod( 'accr_phone_display', '844-484-9628' );
	$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8444849628' );

	/* ----- Final CTA ----- */
	$cta_heading      = $gf ? get_field( 'cta_heading', $post_id ) : '';
	$cta_text         = $gf ? get_field( 'cta_text', $post_id ) : '';
	$cta_button_label = $gf ? get_field( 'cta_button_label', $post_id ) : '';
	$cta_button_url   = $gf ? get_field( 'cta_button_url', $post_id ) : '';
	$cta_image        = $gf ? get_field( 'cta_image', $post_id ) : '';
	if ( ! $cta_heading ) {
		$cta_heading = __( 'We Will Travel to You!', 'accr-theme' );
	}
	if ( ! $cta_text ) {
		$cta_text = __( 'On-site training at your facility for group bookings — we bring the equipment, examiners, and classroom to your location anywhere in the Midwest.', 'accr-theme' );
	}
	if ( ! $cta_button_label ) {
		$cta_button_label = __( 'Contact Us', 'accr-theme' );
	}
	if ( ! $cta_button_url ) {
		$cta_button_url = home_url( '/contact/' );
	}
	$cta_src = is_array( $cta_image ) && ! empty( $cta_image['url'] ) ? $cta_image['url'] : '';
	?>

	<section class="class-hero<?php echo $hero_src ? ' class-hero--has-image' : ''; ?>">
		<?php if ( $hero_src ) : ?>
			<img class="class-hero__image" src="<?php echo esc_url( $hero_src ); ?>" alt="" aria-hidden="true" />
		<?php endif; ?>
		<div class="class-hero__overlay" aria-hidden="true"></div>
		<div class="container class-hero__content">
			<h1 class="class-hero__title"><?php the_title(); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="class-hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
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
				<h2 class="class-panel__heading"><?php echo esc_html( $info_heading ); ?></h2>
				<?php if ( $date ) : ?>
					<p class="class-panel__date"><?php echo wp_kses_post( $date ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $schedule_rows ) ) : ?>
					<ul class="class-schedule" role="list">
						<?php
						$i = 0;
						foreach ( $schedule_rows as $row ) :
							$i++;
							$badge = isset( $row['badge'] ) ? $row['badge'] : '';
							$dt    = isset( $row['datetime'] ) ? $row['datetime'] : '';
							$desc  = isset( $row['description'] ) ? $row['description'] : '';
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

				<div class="class-panel__actions">
					<button class="btn btn--primary btn--lg" data-request-pricing data-class="<?php echo esc_attr( $req_label ); ?>" data-date="<?php echo esc_attr( wp_strip_all_tags( $date ) ); ?>">
						<?php esc_html_e( 'Request Pricing', 'accr-theme' ); ?>
					</button>
					<a class="btn btn--secondary btn--lg" href="<?php echo esc_attr( $phone_link ); ?>">
						<?php echo accr_icon( 'phone' ); ?>
						<?php echo esc_html( sprintf( __( 'Call: %s', 'accr-theme' ), $phone_display ) ); ?>
					</a>
				</div>
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

	<section class="section section--tight">
		<div class="container">
			<div class="class-cta">
				<?php if ( $cta_src ) : ?>
					<img class="class-cta__image" src="<?php echo esc_url( $cta_src ); ?>" alt="" aria-hidden="true" />
				<?php endif; ?>
				<div class="class-cta__content">
					<h2 class="class-cta__title"><?php echo esc_html( $cta_heading ); ?></h2>
					<p class="class-cta__text"><?php echo wp_kses_post( $cta_text ); ?></p>
					<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $cta_button_url ); ?>">
						<?php echo esc_html( $cta_button_label ); ?>
						<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16' ) ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

	<?php
	if ( $has_sections ) {
		accr_render_page_sections( $post_id );
	}
endwhile;

get_footer();
