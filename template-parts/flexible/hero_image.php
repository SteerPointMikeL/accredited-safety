<?php
/**
 * Layout: hero_image — full-bleed home hero with bg image, tagline, title, lead, buttons.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$image   = get_sub_field( 'image' );
$tagline = get_sub_field( 'tagline' );
$title   = get_sub_field( 'title' );
$lead    = get_sub_field( 'lead' );
$buttons = get_sub_field( 'buttons' );
?>
<section class="hero">
	<?php if ( $image ) : ?>
		<?php echo wp_get_attachment_image( $image, 'full', null, array( 'class' => 'hero__image' ) ); ?>
	<?php endif; ?>
	<div class="hero__overlay"></div>
	<div class="container hero__content">
		<div class="hero__content__inner">
			<?php if ( $tagline ) : ?>
				<span class="hero__tagline"><?php echo esc_html( $tagline ); ?></span>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h1 class="hero__title"><?php echo wp_kses_post( $title ); ?></h1>
			<?php endif; ?>
			<?php if ( $lead ) : ?>
				<div class="hero__lead">
					<?php echo wp_kses_post( $lead ); ?>
				</div>
			<?php endif; ?>
			<?php if ( $buttons ) : ?>
				<div class="hero__actions">
					<?php foreach ( $buttons as $b ) {
						echo accr_render_button( $b );
					} ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
