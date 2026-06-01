<?php
/**
 * Site footer.
 *
 * @package ACCR_Theme
 */

$phone_display = get_theme_mod( 'accr_phone_display', '844-717-3665' );
$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8447173665' );
$email         = get_theme_mod( 'accr_email', 'info@accredited-safety.com' );
$copyright     = get_theme_mod( 'accr_copyright', '© ' . date_i18n( 'Y' ) . ' Accredited Safety Solutions. All rights reserved.' );
$legal_line    = get_theme_mod( 'accr_legal_line', 'NCCCO is a registered trademark of the National Commission for the Certification of Crane Operators.' );
?>
</main>

<div class="pre-footer">
	<div class="container pre-footer__inner">
		<p>Trusted NCCCO crane operator certification training — hands-on, practical, and built for real operators.</p>
	</div>
</div>

<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-grid__column">
				<div class="footer-grid__column__inner">
					<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer-logo.webp" alt="Accredited Safety Solutions - Crane Certification Training" width="280" height="237" />
					</a>
					<ul class="site-footer__social-media">
						<li>
							<a href="https://www.facebook.com/accreditedsafetysolutions/" target="_blank" title="Facebook">
								<?php echo accr_icon( 'facebook', array( 'stroke' => 'none', 'fill' => 'currentColor' ) ); ?>
							</a>
						</li>
						<li>
							<a href="https://www.instagram.com/accreditedsafetysolutions/" target="_blank" title="Instagram">
								<?php echo accr_icon( 'instagram', array( 'stroke' => 'none', 'fill' => 'currentColor' ) ); ?>
							</a>
						</li>
						<li>
							<a href="https://www.linkedin.com/company/accredited-safety-solutions-inc" target="_blank" title="LinkedIn">
								<?php echo accr_icon( 'linkedin', array( 'stroke' => 'none', 'fill' => 'currentColor' ) ); ?>
							</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="footer-grid__column" style="margin-top: var(--space-16);">
				<h4>Training</h4>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>', 'depth' => 1 ) ); ?>
				<?php else : ?>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/certifications/' ) ); ?>">Certifications</a></li>
						<li><a href="<?php echo esc_url( home_url( '/classes/' ) ); ?>">Classes</a></li>
						<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
						<li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>">Careers</a></li>
						<li><a href="<?php echo esc_url( home_url( '/blogs/' ) ); ?>">Blogs</a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div class="footer-grid__column" style="margin-top: var(--space-16);">
				<h4>Company</h4>
				<ul>
					<li>Indianapolis, Indiana</li>
					<li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li>
					<li><a href="<?php echo esc_attr( $phone_link ); ?>"><?php echo esc_html( $phone_display ); ?></a></li>
					<li><a href="">Workforce Regulation Statement</a></li>
				</ul>
			</div>

			<div class="footer-grid__column">
				<?php get_template_part( 'template-parts/footer/newsletter' ); ?>
			</div>
		</div>

		<div class="footer-bottom">
			<div><?php echo esc_html( $copyright ); ?></div>
			<div><?php echo esc_html( $legal_line ); ?></div>
		</div>
	</div>
</footer>

<!-- Request Pricing modal (legacy — keep so static buttons with data-request-pricing still work). -->
<div class="modal-backdrop" data-modal="pricing" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pricing-modal-title">
	<div class="modal">
		<div class="modal__head">
			<div>
				<h3 id="pricing-modal-title" data-modal-title>Request Pricing</h3>
				<p data-modal-subtitle>
					Fill in a few details and we'll reply with class pricing, group rates, and
					availability within one business day.
				</p>
			</div>
			<button class="modal__close" data-modal-close aria-label="Close">
				<?php echo accr_icon( 'close', array( 'width' => '18', 'height' => '18', 'stroke-width' => '2.5' ) ); ?>
			</button>
		</div>
		<div class="modal__body">
			<?php
			$pricing_form_id = (int) get_theme_mod( 'accr_pricing_form_id', 0 );
			if ( $pricing_form_id && function_exists( 'gravity_form' ) ) {
				echo accr_render_gravity_form( $pricing_form_id );
			} else {
				/* Static-form fallback so the modal still functions out-of-the-box.
				 * Once Gravity Forms is configured, set "Request Pricing form ID" in the
				 * Customizer and this block will be replaced automatically. */
				?>
				<form novalidate>
					<input type="hidden" name="class_name" value="" />
					<div class="form-row">
						<div class="field">
							<label for="pf-first">First name <span class="req">*</span></label>
							<input id="pf-first" name="first_name" type="text" required autocomplete="given-name" />
						</div>
						<div class="field">
							<label for="pf-last">Last name <span class="req">*</span></label>
							<input id="pf-last" name="last_name" type="text" required autocomplete="family-name" />
						</div>
					</div>
					<div class="field">
						<label for="pf-email">Email <span class="req">*</span></label>
						<input id="pf-email" name="email" type="email" required autocomplete="email" />
					</div>
					<div class="field">
						<label for="pf-phone">Phone <span class="req">*</span></label>
						<input id="pf-phone" name="phone" type="tel" required autocomplete="tel" />
					</div>
					<div class="field">
						<label for="pf-company">Company</label>
						<input id="pf-company" name="company" type="text" autocomplete="organization" />
					</div>
					<div class="field">
						<label for="pf-count">How many operators?</label>
						<select id="pf-count" name="operator_count">
							<option value="1">Just me</option>
							<option value="2-4">2–4</option>
							<option value="5-9">5–9</option>
							<option value="10+">10+ (group pricing)</option>
						</select>
					</div>
					<div class="field">
						<label for="pf-notes">Anything we should know?</label>
						<textarea id="pf-notes" name="notes" placeholder="Preferred dates, on-site request, specific certifications…"></textarea>
					</div>
					<button type="submit" class="btn btn--primary btn--lg btn--block">
						Send request
						<?php echo accr_icon( 'arrow_right', array( 'width' => '16', 'height' => '16', 'stroke-width' => '2.5' ) ); ?>
					</button>
					<p style="font-size: var(--text-xs); color: var(--color-text-muted); margin-top: var(--space-3); text-align:center;">
						We'll never share your info. You'll hear back within 1 business day.
					</p>
				</form>
				<div class="modal__success">
					<?php echo accr_icon( 'check_circle', array( 'stroke-width' => '2.5' ) ); ?>
					<h3>Request received</h3>
					<p>Thanks — someone from our team will be in touch within one business day with pricing and availability.</p>
					<button type="button" class="btn btn--outline" data-modal-close style="margin-top: var(--space-5);">Close</button>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php
$newsletter_modal_heading  = get_theme_mod( 'accr_newsletter_modal_heading', 'Sign up for our newsletter' );
$newsletter_modal_subtitle = get_theme_mod( 'accr_newsletter_modal_subtitle', 'Get NCCCO updates, class schedules, and safety guidance delivered to your inbox.' );
?>
<!-- Newsletter sign-up modal. Opened by the dummy footer newsletter card. -->
<div class="modal-backdrop" id="newsletter-modal" data-modal="newsletter" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="newsletter-modal-title">
	<div class="modal">
		<div class="modal__head">
			<div>
				<?php /* <h3 id="newsletter-modal-title"><?php echo esc_html( $newsletter_modal_heading ); ?></h3>
				<?php if ( $newsletter_modal_subtitle ) : ?>
					<p><?php echo esc_html( $newsletter_modal_subtitle ); ?></p>
				<?php endif; ?> */ ?>
			</div>
			<button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'accr-theme' ); ?>">
				<?php echo accr_icon( 'close', array( 'width' => '18', 'height' => '18', 'stroke-width' => '2.5' ) ); ?>
			</button>
		</div>
		<div class="modal__body">
			<?php /*
			$newsletter_form_id = (int) get_theme_mod( 'accr_newsletter_form_id', 0 );
			if ( $newsletter_form_id ) {
				echo accr_render_gravity_form( $newsletter_form_id );
			} else {
				?>
				<div class="gf-placeholder" style="border:1px dashed var(--color-divider); padding: var(--space-6); border-radius: var(--radius-md); color: var(--color-text-muted);">
					<?php
					if ( current_user_can( 'edit_theme_options' ) ) {
						esc_html_e( 'Set a "Newsletter Gravity Forms ID" in Appearance → Customize → Footer Newsletter to render the sign-up form here.', 'accr-theme' );
					} else {
						esc_html_e( 'Our newsletter sign-up form is being set up. Please check back soon.', 'accr-theme' );
					}
					?>
				</div>
				<?php
			}
			*/ ?>
			<!-- Begin Constant Contact Inline Form Code -->
			<div class="ctct-inline-form" data-form-id="b5fb3f51-4966-4a12-95a0-f3b9578a0dd0"></div>
			<!-- End Constant Contact Inline Form Code -->

			<!-- Begin Constant Contact Active Forms -->
			<script> var _ctct_m = "bb2fd9489bb6631de231d808467dfae5"; </script>
			<script id="signupScript" src="//static.ctctcdn.com/js/signup-form-widget/current/signup-form-widget.min.js" async defer></script>
			<!-- End Constant Contact Active Forms -->
		</div>
	</div>
</div>

<!-- wp_footer() -->
<?php wp_footer(); ?>
<!-- end wp_footer() -->
</body>
</html>
