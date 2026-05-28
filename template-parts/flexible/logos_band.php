<?php
/**
 * Layout: logos_band
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$items   = get_sub_field( 'items' );
?>
<section class="section section--sm">
	<div class="container">
		<?php if ( $eyebrow || $title ) : ?>
			<div class="text-center" style="margin-bottom: var(--space-10);">
				<?php if ( $eyebrow ) : ?><span class="eyebrow" style="justify-content:center;"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="section-title" style="font-size: var(--text-xl);"><?php echo esc_html( $title ); ?></h2><?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $items ) : ?>
			<div class="logos">
				<?php foreach ( $items as $it ) : ?>
					<div class="logo-tile">
						<?php if ( ! empty( $it['image'] ) && is_array( $it['image'] ) ) : ?>
							<img src="<?php echo esc_url( $it['image']['url'] ); ?>" alt="<?php echo esc_attr( $it['image']['alt'] ?? '' ); ?>" />
						<?php else : ?>
							<?php echo wp_kses_post( $it['label'] ?? '' ); ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
