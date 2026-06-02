<?php
/**
 * Layout: notice_bar
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$tone = get_sub_field( 'tone' ) ?: 'warning';
$title = get_sub_field( 'title' );
$text = get_sub_field( 'text' );

?>
<section class="notice-bar notice-bar-<?php echo $tone; ?>">
	<div class="container">
		<p class="notice-bar__title">
			<?php echo accr_icon( 'alert', array( 'width' => '22', 'height' => '22', 'fill' => 'currentColor', 'stroke' => 'none' ) ); ?>
			<?php echo wp_kses_post( $title ); ?>
		</p>
		<?php echo $text; ?>
	</div>
</section>
