<?php
/**
 * Layout: cards_grid — versatile card grid for certs, features, steps, testimonials.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$lead    = get_sub_field( 'lead' );
$variant = get_sub_field( 'variant' ) ?: 'feature';
$cols    = get_sub_field( 'columns' ) ?: '3';
$bg      = get_sub_field( 'background' ) ?: 'default';
$cards   = get_sub_field( 'cards' );
$footer  = get_sub_field( 'footer_button' );

$grid_class = 'card-grid card-grid--' . intval( $cols );

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

		<?php if ( $cards ) : ?>
			<div class="<?php echo esc_attr( $grid_class ); ?>">
				<?php foreach ( $cards as $card ) :
					$anchor = ! empty( $card['anchor'] ) ? ' id="' . esc_attr( $card['anchor'] ) . '"' : ''; ?>
						<article<?php echo $anchor; ?> class="feature-card">
							<div class="feature-card__icon">
								<?php
								if ( 'feature_num' === $variant && ! empty( $card['number'] ) ) {
									echo '<strong style="font-family: var(--font-display); font-size: var(--text-lg);">' . esc_html( $card['number'] ) . '</strong>';
								} elseif ( ! empty( $card['icon'] ) ) {
									echo accr_icon( $card['icon'] );
								}
								?>
							</div>
							<?php if ( ! empty( $card['title'] ) ) : ?><h3><?php echo esc_html( $card['title'] ); ?></h3><?php endif; ?>
							<?php if ( ! empty( $card['body'] ) ) : ?><p><?php echo wp_kses_post( $card['body'] ); ?></p><?php endif; ?>
							<?php if ( ! empty( $card['link_label'] ) ) : ?>
								<div style="margin-top: var(--space-5);">
									<a class="btn btn--outline" href="<?php echo esc_url( $card['link_url'] ?? '#' ); ?>" style="font-size: var(--text-xs); padding: var(--space-2) var(--space-4);"><?php echo esc_html( $card['link_label'] ); ?></a>
								</div>
							<?php endif; ?>
						</article>
				<?php endforeach; ?>
			</div>
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
