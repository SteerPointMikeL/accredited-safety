<?php
/**
 * Layout: section_intro — eyebrow + title + lead, no other content (use cards_grid or split for the body).
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$lead    = get_sub_field( 'lead' );
$align   = get_sub_field( 'align' ) ?: 'left';
$bg      = get_sub_field( 'background' ) ?: 'default';

$wrap_style = 'max-width: 760px; margin-bottom: var(--space-12);';
if ( 'center' === $align ) {
	$wrap_style .= ' margin-left: auto; margin-right: auto; text-align: center;';
}

accr_section_open( array( 'background' => $bg ) );
?>
	<div class="container">
		<div style="<?php echo esc_attr( $wrap_style ); ?>">
			<?php if ( $eyebrow ) : ?>
				<span class="eyebrow"<?php echo 'center' === $align ? ' style="justify-content:center;"' : ''; ?>><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( $lead ) : ?>
				<p class="section-lead"><?php echo wp_kses_post( $lead ); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php
accr_section_close();
