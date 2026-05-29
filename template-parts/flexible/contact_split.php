<?php
/**
 * Layout: contact_split — left intro + contact rows, right Gravity Form panel.
 *
 * Matches the Figma "Contact Us" frame (node 187:998):
 *  - Left column: orange eyebrow, navy heading, intro body, then stacked
 *    contact rows separated by horizontal dividers (label / value / note).
 *  - Right column: light-gray panel (#f2f4f7, 48px padding) with uppercase
 *    navy heading and a Gravity Form rendered via accr_render_gravity_form().
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$intro_eyebrow = get_sub_field( 'intro_eyebrow' );
$intro_heading = get_sub_field( 'intro_heading' );
$intro_body    = get_sub_field( 'intro_body' );
$rows          = get_sub_field( 'rows' );
$form_heading  = get_sub_field( 'form_heading' );
if ( ! $form_heading ) {
	$form_heading = get_sub_field( 'form_title' );
}
$form_lead = get_sub_field( 'form_lead' );
$gf_id     = (int) get_sub_field( 'gravity_form_id' );
?>
<section class="section contact-split">
	<div class="container">
		<div class="contact-split__grid">

			<div class="contact-split__info">
				<?php if ( $intro_eyebrow || $intro_heading || $intro_body ) : ?>
					<header class="contact-split__intro">
						<?php if ( $intro_eyebrow ) : ?>
							<p class="contact-split__eyebrow"><?php echo esc_html( $intro_eyebrow ); ?></p>
						<?php endif; ?>
						<?php if ( $intro_heading ) : ?>
							<h2 class="contact-split__heading"><?php echo wp_kses_post( $intro_heading ); ?></h2>
						<?php endif; ?>
						<?php if ( $intro_body ) : ?>
							<div class="contact-split__lead"><?php echo wp_kses_post( wpautop( $intro_body ) ); ?></div>
						<?php endif; ?>
					</header>
				<?php endif; ?>

				<?php if ( $rows ) : ?>
					<ul class="contact-split__rows">
						<?php foreach ( $rows as $row ) :
							$label = $row['label'] ?? '';
							$value = $row['value'] ?? '';
							$href  = $row['value_href'] ?? '';
							$note  = $row['note'] ?? '';
							if ( ! $label && ! $value && ! $note ) { continue; }
							?>
							<li class="contact-split__row">
								<?php if ( $label ) : ?>
									<span class="contact-split__row-label"><?php echo esc_html( $label ); ?></span>
								<?php endif; ?>
								<?php if ( $value ) :
									if ( $href ) : ?>
										<a class="contact-split__row-value" href="<?php echo esc_attr( $href ); ?>"><?php echo esc_html( $value ); ?></a>
									<?php else : ?>
										<span class="contact-split__row-value"><?php echo esc_html( $value ); ?></span>
									<?php endif;
								endif; ?>
								<?php if ( $note ) : ?>
									<div class="contact-split__row-note"><?php echo wp_kses_post( wpautop( $note ) ); ?></div>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="contact-split__form-panel">
				<?php if ( $form_heading ) : ?>
					<h2 class="contact-split__form-heading"><?php echo esc_html( $form_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $form_lead ) : ?>
					<div class="contact-split__form-lead"><?php echo wp_kses_post( wpautop( $form_lead ) ); ?></div>
				<?php endif; ?>

				<div class="contact-split__form">
					<?php if ( $gf_id ) :
						echo accr_render_gravity_form( $gf_id );
					else : ?>
						<div class="gf-placeholder">
							Set a Gravity Form ID on this section in the page editor to render the contact form here.
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</section>
