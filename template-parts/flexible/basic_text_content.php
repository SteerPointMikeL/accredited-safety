<?php
/**
 * Layout: basic_text_content — eyebrow + title + WYSIWYG page content.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$content = get_sub_field( 'content' );
$bg      = get_sub_field( 'background' ) ?: 'default';

accr_section_open( array( 'background' => $bg ) );
?>
	<div class="container">
		<?php if ( $eyebrow ) : ?>
			<span class="eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h2 class="section-title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>
		<?php if ( $content ) : ?>
			<div class="rich-content"><?php echo wp_kses_post( $content ); ?></div>
		<?php endif; ?>
	</div>
<?php
accr_section_close();
