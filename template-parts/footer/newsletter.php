<?php
/**
 * Footer newsletter sign-up card.
 *
 * The card is a dummy/presentational preview matching the Figma design. The
 * whole card is a single accessible trigger that opens the newsletter modal
 * (rendered in footer.php) containing the real form. The inner "input" and
 * "button" are non-interactive decoration only.
 *
 * @package ACCR_Theme
 */

$heading     = get_theme_mod( 'accr_newsletter_heading', 'Sign up for our newsletter' );
$description  = get_theme_mod( 'accr_newsletter_description', 'Stay current on NCCCO requirements, upcoming classes, and safety regulations.' );
?>
<button type="button" class="newsletter-card" data-newsletter-open aria-haspopup="dialog" aria-controls="newsletter-modal" aria-expanded="false">
	<span class="newsletter-card__heading"><?php echo esc_html( $heading ); ?></span>
	<span class="newsletter-card__desc"><?php echo esc_html( $description ); ?></span>
	<span class="newsletter-card__field" aria-hidden="true"><?php esc_html_e( 'Your Email Address', 'accr-theme' ); ?></span>
	<span class="newsletter-card__submit" aria-hidden="true">
		<?php echo accr_icon( 'mail', array( 'width' => '18', 'height' => '18', 'stroke-width' => '2' ) ); ?>
		<span><?php esc_html_e( 'Submit', 'accr-theme' ); ?></span>
	</span>
	<span class="sr-only"><?php esc_html_e( 'Open the newsletter sign-up form', 'accr-theme' ); ?></span>
</button>
