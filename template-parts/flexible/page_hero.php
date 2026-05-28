<?php
/**
 * Layout: page_hero
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$eyebrow = get_sub_field( 'eyebrow' );
$title   = get_sub_field( 'title' );
$lead    = get_sub_field( 'lead' );
?>
<section class="page-hero">
	<div class="container">
		<?php if ( $eyebrow ) : ?>
			<span class="page-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h1 class="page-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php endif; ?>
		<?php if ( $lead ) : ?>
			<p class="page-hero__lead"><?php echo wp_kses_post( $lead ); ?></p>
		<?php endif; ?>
	</div>
</section>
