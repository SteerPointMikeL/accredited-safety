<?php
/**
 * Layout: openings_list — career openings rows.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$items   = get_sub_field( 'items' );

accr_section_open( array( 'background' => 'surface_2' ) );
?>
	<div class="container">
		<?php if ( $eyebrow || $title ) : ?>
			<div style="max-width: 760px; margin-bottom: var(--space-10)">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div style="display:grid; gap: var(--space-4);">
				<?php foreach ( $items as $it ) : ?>
					<article style="background: var(--color-surface); border: 1px solid var(--color-divider); border-radius: var(--radius-lg); padding: var(--space-6); display:grid; grid-template-columns: 1fr auto; gap: var(--space-5); align-items:center;">
						<div>
							<?php if ( ! empty( $it['type'] ) || ! empty( $it['location'] ) ) : ?>
								<div style="display:flex; gap: var(--space-2); margin-bottom: var(--space-2); flex-wrap:wrap;">
									<?php if ( ! empty( $it['type'] ) ) : ?>
										<span style="font-family: var(--font-display); text-transform:uppercase; letter-spacing: 0.1em; font-size: var(--text-xs); background: var(--color-orange-highlight); color: var(--color-orange); padding: 2px 10px; border-radius: var(--radius-sm); font-weight:700;"><?php echo esc_html( $it['type'] ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $it['location'] ) ) : ?>
										<span style="font-family: var(--font-display); text-transform:uppercase; letter-spacing: 0.1em; font-size: var(--text-xs); color: var(--color-text-muted); padding: 2px 10px;"><?php echo esc_html( $it['location'] ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if ( ! empty( $it['title'] ) ) : ?>
								<h3 style="font-size: var(--text-lg); margin-bottom: var(--space-2);"><?php echo esc_html( $it['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $it['body'] ) ) : ?>
								<p style="color: var(--color-text-muted); font-size: var(--text-sm);"><?php echo wp_kses_post( $it['body'] ); ?></p>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $it['apply_url'] ) ) : ?>
							<a href="<?php echo esc_attr( $it['apply_url'] ); ?>" class="btn btn--primary"><?php echo esc_html( ! empty( $it['apply_label'] ) ? $it['apply_label'] : 'Apply' ); ?></a>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
<?php
accr_section_close();
