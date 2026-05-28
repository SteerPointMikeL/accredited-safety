<?php
/**
 * Layout: gravity_form — standalone Gravity Forms render.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$lead    = get_sub_field( 'lead' );
$form_id = (int) get_sub_field( 'form_id' );
?>
<section class="section">
	<div class="container" style="max-width: 720px;">
		<?php if ( $eyebrow || $title || $lead ) : ?>
			<div style="margin-bottom: var(--space-8);">
				<?php if ( $eyebrow ) : ?><span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span><?php endif; ?>
				<?php if ( $title ) : ?><h2 class="section-title"><?php echo esc_html( $title ); ?></h2><?php endif; ?>
				<?php if ( $lead ) : ?><p class="section-lead"><?php echo wp_kses_post( $lead ); ?></p><?php endif; ?>
			</div>
		<?php endif; ?>
		<?php echo accr_render_gravity_form( $form_id ); ?>
	</div>
</section>
