<?php
/**
 * Layout: trust_bar
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$label = get_sub_field( 'label' );
$items = get_sub_field( 'items' );
?>
<section class="trust-bar">
	<div class="container trust-bar__inner">
		<?php if ( $label ) : ?>
			<div class="trust-bar__label"><?php echo wp_kses_post( $label ); ?></div>
		<?php endif; ?>
		<?php if ( $items ) : ?>
			<div class="trust-bar__list">
				<?php foreach ( $items as $it ) : ?>
					<div class="trust-bar__item">
						<?php echo accr_icon( $it['icon'] ?: 'shield' ); ?>
						<?php echo esc_html( $it['label'] ?? '' ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
