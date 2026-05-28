<?php
/**
 * Layout: split — two-column text + image. Reused for "Hands-on" section,
 * mission, every certification split, etc. Supports reverse, bullets, buttons.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow         = get_sub_field( 'eyebrow' );
$title           = get_sub_field( 'title' );
$body            = get_sub_field( 'body' );
$bullets_heading = get_sub_field( 'bullets_heading' );
$bullets         = get_sub_field( 'bullets' );
$bullet_style    = get_sub_field( 'bullet_style' ) ?: 'check';
$buttons         = get_sub_field( 'buttons' );
$image           = get_sub_field( 'image' );
$reverse         = get_sub_field( 'reverse' );
$bg              = get_sub_field( 'background' ) ?: 'default';
$anchor          = get_sub_field( 'anchor' );

$split_class = 'split' . ( $reverse ? ' split--reverse' : '' );

accr_section_open( array( 'background' => $bg, 'id' => $anchor ) );
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

				<?php if ( $body ) : ?>
					<div class="split__body" style="color: var(--color-text-muted); font-size: var(--text-base);">
						<?php echo wp_kses_post( $body ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $bullets_heading ) : ?>
					<h3 style="font-size: var(--text-base); margin-bottom: var(--space-3); text-transform:uppercase; letter-spacing:0.08em; color: var(--color-text);"><?php echo esc_html( $bullets_heading ); ?></h3>
				<?php endif; ?>

				<?php if ( $bullets ) :
					$ul_style = 'list-style: none; display: grid; gap: var(--space-3); margin: var(--space-4) 0 var(--space-6);font-size: var(--text-sm);';
					if ( 'arrow_2col' === $bullet_style ) {
						$ul_style = 'display:grid; grid-template-columns: 1fr 1fr; gap: var(--space-2); margin: var(--space-4) 0 var(--space-6); list-style:none;font-size: var(--text-sm);';
					} elseif ( 'arrow' === $bullet_style ) {
						$ul_style = 'display:grid; gap: var(--space-2); margin: var(--space-4) 0 var(--space-6); list-style:none;font-size: var(--text-sm);';
					}
					?>
					<ul style="<?php echo esc_attr( $ul_style ); ?>">
						<?php foreach ( $bullets as $b ) :
							if ( empty( $b['text'] ) ) continue;
							if ( 'check' === $bullet_style ) : ?>
								<li style="display:flex; gap: var(--space-3); align-items:flex-start;">
									<?php echo accr_icon( 'check', array( 'width' => '20', 'height' => '20', 'stroke-width' => '2.5', 'style' => 'flex-shrink:0; margin-top:4px; color: var(--color-orange);' ) ); ?>
									<span><?php echo esc_html( $b['text'] ); ?></span>
								</li>
							<?php else : ?>
								<li style="display:flex; gap: var(--space-2);"><span style="color: var(--color-orange)">▸</span> <?php echo esc_html( $b['text'] ); ?></li>
							<?php endif;
						endforeach; ?>
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
