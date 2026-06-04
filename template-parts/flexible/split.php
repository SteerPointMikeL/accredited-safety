<?php
/**
 * Layout: split — two-column text + image. Reused for "Hands-on" section,
 * mission, every certification split, etc. Supports reverse, bullets, buttons.
 *
 * The "navy_band" background variant renders the Figma "Content w/Image
 * (Alternate)" treatment: a blue gradient band behind the text column, with
 * the image overflowing the band's top and bottom edges. Pairs with the
 * optional `features` repeater (two mini columns under the body).
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$section_padding           = get_sub_field( 'section_padding' ) ?: 'default';
$column_space_distribution = get_sub_field( 'column_space_distribution' );
$eyebrow                   = get_sub_field( 'eyebrow' );
$title                     = get_sub_field( 'title' );
$body                      = get_sub_field( 'body' );
$bullets_heading           = get_sub_field( 'bullets_heading' );
$bullets                   = get_sub_field( 'bullets' );
$bullet_style              = get_sub_field( 'bullet_style' ) ?: 'check';
$features                  = get_sub_field( 'features' );
$buttons                   = get_sub_field( 'buttons' );
$image                     = get_sub_field( 'image' );
$reverse                   = get_sub_field( 'reverse' );
$overflowing_image         = get_sub_field( 'overflowing_image' );
$bg                        = get_sub_field( 'background' ) ?: 'default';
$anchor                    = get_sub_field( 'anchor' );

$split_class = 'split';
if ( $column_space_distribution ) {
	$split_class .= ' split--' . $column_space_distribution;
}
if ( $reverse ) {
	$split_class .= ' split--reverse';
}
if ( $overflowing_image ) {
	$split_class .= ' split--overflowing-image';
}

accr_section_open( array(
	'background' => $bg,
	'id'         => $anchor,
	'class'      => 'section section--' . $section_padding,
) );
?>
	<div class="container">
		<div class="<?php echo esc_attr( $split_class ); ?>">
			<div class="split__text">
				<?php if ( $eyebrow ) : ?>
					<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $features ) : ?>
					<div class="split__features">
						<?php foreach ( $features as $f ) :
							if ( empty( $f['title'] ) && empty( $f['body'] ) ) continue; ?>
							<div class="split__feature">
								<?php if ( ! empty( $f['title'] ) ) : ?>
									<h4 class="split__feature-title">
										<?php if ( ! empty( $f['icon'] ) ) :
											echo accr_icon( $f['icon'], array( 'width' => '20', 'height' => '20', 'class' => 'split__feature-icon' ) );
										endif; ?>
										<span><?php echo esc_html( $f['title'] ); ?></span>
									</h4>
								<?php endif; ?>
								<?php if ( ! empty( $f['body'] ) ) : ?>
									<p class="split__feature-body"><?php echo wp_kses_post( $f['body'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $body ) : ?>
					<div class="split__body">
						<?php echo wp_kses_post( $body ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $bullets_heading ) : ?>
					<h3 class="split__bullets--heading"><?php echo esc_html( $bullets_heading ); ?></h3>
				<?php endif; ?>

				<?php if ( $bullets ) :
					$bullets_class = 'split__bullets';
					if ( 'arrow' === $bullet_style ) {
						$bullets_class .= ' .split__bullets--arrow';
					} elseif ( 'arrow_2col' === $bullet_style ) {
						$bullets_class .= ' .split__bullets--arrow-2-column';
					} elseif ( 'check' === $bullet_style ) {
						$bullets_class .= ' .split__bullets--check';
					}
					?>
					<ul class="<?php echo $bullets_class; ?>">
						<?php foreach ( $bullets as $b ) :
							if ( empty( $b['text'] ) ) continue; ?>
								<li>
								<?php if ( 'check' === $bullet_style ) : ?>
									<?php echo accr_icon( 'check', array( 'width' => '20', 'height' => '20', 'stroke-width' => '2.5', 'style' => 'flex-shrink:0; margin-top:4px; color: var(--color-orange);' ) ); ?>
									<span><?php echo esc_html( $b['text'] ); ?></span>
								<?php endif; ?>
								</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $buttons ) : ?>
					<?php echo accr_render_buttons( $buttons ); ?>
				<?php endif; ?>
			</div>

			<?php if ( $image && is_array( $image ) ) : ?>
				<div class="split__image">
					<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" />
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php
accr_section_close();
