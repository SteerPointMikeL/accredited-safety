<?php
/**
 * Layout: stats_band
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$items = get_sub_field( 'items' );
if ( empty( $items ) ) return;
?>
<section class="stats">
	<div class="container stats__grid">
		<?php foreach ( $items as $it ) : ?>
			<div class="stat">
				<span class="stat__value"><?php echo esc_html( $it['value'] ?? '' ); ?></span>
				<span class="stat__label"><?php echo esc_html( $it['label'] ?? '' ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
