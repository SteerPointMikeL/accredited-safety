<?php
/**
 * Layout: cta_banner
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$title   = get_sub_field( 'title' );
$text    = get_sub_field( 'text' );
$buttons = get_sub_field( 'buttons' );
$bg      = get_sub_field( 'background' ) ?: 'light_grey';

accr_section_open( array( 'class' => 'section section--tight' ) );
?>
	<div class="container">
		<div class="cta-banner cta-banner--<?php echo esc_attr( $bg ); ?>">
			<div class="cta-banner__inner">
				<div>
					<?php if ( $title ) : ?><h2><?php echo esc_html( $title ); ?></h2><?php endif; ?>
					<?php if ( $text ) : ?><p><?php echo wp_kses_post( $text ); ?></p><?php endif; ?>
				</div>
				<?php if ( $buttons ) : ?>
					<?php echo accr_render_buttons( $buttons ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
accr_section_close();
