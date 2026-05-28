<?php
/**
 * Layout: contact_split — left info blocks + right form card.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$blocks     = get_sub_field( 'blocks' );
$form_title = get_sub_field( 'form_title' );
$form_lead  = get_sub_field( 'form_lead' );
$gf_id      = (int) get_sub_field( 'gravity_form_id' );
?>
<section class="section">
	<div class="container">
		<div style="display:grid; grid-template-columns: 1fr 1.3fr; gap: clamp(var(--space-8), 5vw, var(--space-16)); align-items:start;" class="contact-split-grid">

			<!-- Left: info blocks -->
			<div style="display:grid; gap: var(--space-6);">
				<?php if ( $blocks ) :
					$first = true;
					foreach ( $blocks as $b ) :
						$wrap_style = $first ? '' : 'border-top: 1px solid var(--color-divider); padding-top: var(--space-6);';
						$first = false;
						?>
						<div style="<?php echo esc_attr( $wrap_style ); ?>">
							<?php if ( ! empty( $b['eyebrow'] ) ) : ?>
								<span class="eyebrow"><?php echo esc_html( $b['eyebrow'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $b['title'] ) ) : ?>
								<h2 style="font-size: var(--text-lg); margin-top: var(--space-3); margin-bottom: var(--space-2);"><?php echo esc_html( $b['title'] ); ?></h2>
							<?php endif; ?>

							<?php
							$emphasis     = $b['emphasis'] ?? 'none';
							$emp_label    = $b['emphasis_label'] ?? '';
							$emp_url      = $b['emphasis_url'] ?? '';
							switch ( $emphasis ) :
								case 'phone': ?>
									<a href="<?php echo esc_attr( $emp_url ); ?>" style="font-family: var(--font-display); font-size: var(--text-2xl); color: var(--color-orange); text-decoration:none; font-weight:800; letter-spacing:0.02em;"><?php echo esc_html( $emp_label ); ?></a>
									<?php break;
								case 'email': ?>
									<a href="<?php echo esc_attr( $emp_url ); ?>" style="color: var(--color-text); font-weight: 600;"><?php echo esc_html( $emp_label ); ?></a>
									<?php break;
								case 'button': ?>
									<a href="<?php echo esc_url( $emp_url ); ?>" class="btn btn--outline" style="font-size: var(--text-xs); padding: var(--space-2) var(--space-4);"><?php echo esc_html( $emp_label ); ?></a>
									<?php break;
							endswitch; ?>

							<?php if ( ! empty( $b['body'] ) ) : ?>
								<p style="color: var(--color-text-muted); font-size: var(--text-sm); margin-top: var(--space-2);"><?php echo wp_kses_post( $b['body'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach;
				endif; ?>
			</div>

			<!-- Right: form card -->
			<div style="background: var(--color-surface); border: 1px solid var(--color-divider); border-radius: var(--radius-lg); padding: clamp(var(--space-6), 4vw, var(--space-10)); box-shadow: var(--shadow-sm);" class="contact-form-card">
				<?php if ( $form_title ) : ?>
					<h2 style="font-size: var(--text-xl); margin-bottom: var(--space-2);"><?php echo esc_html( $form_title ); ?></h2>
				<?php endif; ?>
				<?php if ( $form_lead ) : ?>
					<p style="color: var(--color-text-muted); font-size: var(--text-sm); margin-bottom: var(--space-6);"><?php echo wp_kses_post( $form_lead ); ?></p>
				<?php endif; ?>

				<?php if ( $gf_id ) : ?>
					<?php echo accr_render_gravity_form( $gf_id ); ?>
				<?php else : ?>
					<div class="gf-placeholder" style="border:1px dashed var(--color-divider); padding: var(--space-6); border-radius: var(--radius-md); color: var(--color-text-muted);">
						Set a Gravity Form ID on this section in the page editor to render the contact form here.
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
