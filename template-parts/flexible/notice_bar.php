<?php
/**
 * Layout: notice_bar
 *
 * @package ACCR_Theme
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$tone = get_sub_field( 'tone' ) ?: 'warning';
$text = get_sub_field( 'text' );

$bg_map = array(
	'warning' => 'var(--color-warning-highlight)',
	'info'    => 'var(--color-info-highlight, var(--color-surface-2))',
	'success' => 'var(--color-success-highlight, var(--color-surface-2))',
);
$bg = isset( $bg_map[ $tone ] ) ? $bg_map[ $tone ] : $bg_map['warning'];

$stroke_map = array(
	'warning' => 'var(--color-warning)',
	'info'    => 'var(--color-info, var(--color-text))',
	'success' => 'var(--color-success)',
);
$stroke = isset( $stroke_map[ $tone ] ) ? $stroke_map[ $tone ] : $stroke_map['warning'];
?>
<section class="notice-bar" style="padding-block: clamp(var(--space-2), 2vw, var(--space-8));background: <?php echo esc_attr( $bg ); ?>;">
	<div class="container" style="display:flex; justify-content:center; align-items:center; gap: var(--space-4); flex-wrap:wrap;">
		<?php /* <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="<?php echo esc_attr( $stroke ); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/>
			<path d="M12 8v4M12 16h.01"/>
		</svg> */ ?>
		<svg width="24" height="24" viewBox="0 0 24 24" fill="<?php echo esc_attr( $stroke ); ?>" xmlns="http://www.w3.org/2000/svg">
			<path d="M3.66346 20.6315C2.51734 19.5245 1.60315 18.2004 0.974242 16.7363C0.345334 15.2723 0.0142989 13.6976 0.000453081 12.1043C-0.0133927 10.5109 0.290228 8.93077 0.893598 7.45601C1.49697 5.98125 2.38801 4.64143 3.51472 3.51472C4.64143 2.38801 5.98125 1.49697 7.45601 0.893598C8.93077 0.290228 10.5109 -0.0133927 12.1043 0.000453081C13.6976 0.0142989 15.2723 0.345334 16.7363 0.974242C18.2004 1.60315 19.5245 2.51734 20.6315 3.66346C22.8174 5.92668 24.0269 8.95791 23.9995 12.1043C23.9722 15.2506 22.7102 18.2604 20.4853 20.4853C18.2604 22.7102 15.2506 23.9722 12.1043 23.9995C8.95791 24.0269 5.92668 22.8174 3.66346 20.6315ZM10.9475 6.14746V13.3475H13.3475V6.14746H10.9475ZM10.9475 15.7475V18.1475H13.3475V15.7475H10.9475Z" />
		</svg>

		<p style="margin:0; font-size: var(--text-sm); color: var(--color-text);"><?php echo wp_kses_post( $text ); ?></p>
	</div>
</section>
