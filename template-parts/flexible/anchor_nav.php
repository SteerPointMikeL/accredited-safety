<?php
/**
 * Layout: anchor_nav — sticky in-page link bar.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$items = get_sub_field( 'items' );
if ( empty( $items ) ) return;
?>
<section class="section--tight anchor-nav" style="background: var(--color-surface); border-bottom: 1px solid var(--color-divider); position:sticky; top: 88px; z-index: 10;">
	<div class="container" style="display:flex; gap: var(--space-4); flex-wrap:wrap; padding-block: var(--space-4);">
		<?php foreach ( $items as $it ) : ?>
			<a href="#<?php echo esc_attr( $it['anchor'] ?? '' ); ?>" class="btn btn--outline" style="font-size: var(--text-xs); padding: var(--space-2) var(--space-4);"><?php echo esc_html( $it['label'] ?? '' ); ?></a>
		<?php endforeach; ?>
	</div>
</section>
