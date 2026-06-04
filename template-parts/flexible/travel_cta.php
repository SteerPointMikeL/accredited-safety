<?php
/**
 * Layout: travel_cta — image + content + button CTA, used for the
 * "We Will Travel to You" section on class posts (and any page).
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$heading      = get_sub_field( 'heading' );
$text         = get_sub_field( 'text' ) ?: '';
$button_label = get_sub_field( 'button_label' ) ?: __( 'Contact Us', 'accr-theme' );
$button_url   = get_sub_field( 'button_url' );
$image        = get_sub_field( 'image' );
$bg           = get_sub_field( 'background' ) ?: 'light_grey';

$img_src = is_array( $image ) && ! empty( $image['url'] ) ? $image['url'] : '';

accr_section_open( array( 'class' => 'section section--tight' ) );
?>
	<div class="container">
		<div class="class-cta<?php echo ! $img_src ?? ' class-cta--no-image'; ?> class-cta--<?php echo esc_attr( $bg ); ?>">
			<?php if ( $img_src ) : ?>
				<img class="class-cta__image" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>" />
			<?php endif; ?>
			<div class="class-cta__content">
				<?php if ( ! empty( $heading ) ) : ?>
					<h2 class="class-cta__title"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>

				<?php echo wp_kses_post( $text ); ?>

				<?php if ( ! empty( $button_url ) ) : ?>
					<div class="class-cta__actions">
						<a class="btn btn--primary btn--lg" href="<?php echo esc_url( $button_url ); ?>">
							<?php echo esc_html( $button_label ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
accr_section_close();
