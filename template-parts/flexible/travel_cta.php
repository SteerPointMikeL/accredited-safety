<?php
/**
 * Layout: travel_cta — image + content + button CTA, used for the
 * "We Will Travel to You" section on class posts (and any page).
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$heading      = get_sub_field( 'heading' );
$text         = get_sub_field( 'text' );
$button_label = get_sub_field( 'button_label' );
$button_url   = get_sub_field( 'button_url' );
$image        = get_sub_field( 'image' );

if ( ! $heading ) {
	$heading = __( 'We Will Travel to You!', 'accr-theme' );
}
if ( ! $text ) {
	$text = __( 'On-site training at your facility for group bookings — we bring the equipment, examiners, and classroom to your location anywhere in the Midwest.', 'accr-theme' );
}
if ( ! $button_label ) {
	$button_label = __( 'Contact Us', 'accr-theme' );
}
if ( ! $button_url ) {
	$button_url = home_url( '/contact/' );
}
$img_src = is_array( $image ) && ! empty( $image['url'] ) ? $image['url'] : '';
?>
<section class="section section--tight">
	<div class="container">
		<div class="class-cta<?php echo $img_src ? '' : ' class-cta--no-image'; ?>">
			<?php if ( $img_src ) : ?>
				<img class="class-cta__image" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" />
			<?php endif; ?>
			<div class="class-cta__content">
				<h2 class="class-cta__title"><?php echo esc_html( $heading ); ?></h2>
				<p class="class-cta__text"><?php echo wp_kses_post( $text ); ?></p>
				<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $button_url ); ?>">
					<?php echo esc_html( $button_label ); ?>
					<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16' ) ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
