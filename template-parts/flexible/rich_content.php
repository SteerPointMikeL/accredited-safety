<?php
/**
 * Layout: rich_content — WYSIWYG fallback.
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$content = get_sub_field( 'content' );
$bg      = get_sub_field( 'background' ) ?: 'default';

accr_section_open( array( 'background' => $bg ) );
?>
	<div class="container">
		<div class="rich-content"><?php echo wp_kses_post( $content ); ?></div>
	</div>
<?php
accr_section_close();
