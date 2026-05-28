<?php
/**
 * Site footer.
 *
 * @package ACCR_Theme
 */

$phone_display = get_theme_mod( 'accr_phone_display', '844-717-3665' );
$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8447173665' );
$email         = get_theme_mod( 'accr_email', 'info@accredited-safety.com' );
//$footer_blurb  = get_theme_mod( 'accr_footer_blurb', "Indiana's trusted NCCCO crane operator certification training — hands-on, practical, and built for real operators." );
//$service_area  = get_theme_mod( 'accr_service_area', 'Serving Indiana & the Midwest' );
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
						<?php /* <svg class="logo__mark" viewBox="0 0 48 48" fill="none" aria-hidden="true">
							<rect x="2" y="2" width="44" height="44" rx="4" fill="#0F2A3D" stroke="#EE6A19" stroke-width="2.5"/>
							<path d="M10 34 L24 12 L38 34 Z" fill="none" stroke="#EE6A19" stroke-width="3" stroke-linejoin="round"/>
							<circle cx="24" cy="27" r="3.2" fill="#EE6A19"/>
							<path d="M24 34 L24 40" stroke="#EE6A19" stroke-width="2.5" stroke-linecap="round"/>
						</svg>
						<span class="logo__text">
							<span class="logo__top">Accredited</span>
							<span class="logo__bot">Safety Solutions</span>
						</span> */ ?>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/footer-logo.webp" alt="Accredited Safety Solutions - Crane Certification Training" width="280" height="237" />
					</a>
					<?php /* <p style="margin-top: var(--space-4); color: rgb(255 255 255 / 0.6); max-width: 36ch;">
						<?php echo esc_html( $footer_blurb ); ?>
					</p> */ ?>
					<ul class="site-footer__social-media">
						<li>
							<a href="https://www.facebook.com/accreditedsafetysolutions/" target="_blank" title="Facebook">
								<svg width="19" height="19" viewBox="0 0 19 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M13.0388 19V11.6423H15.5088L15.8783 8.77515H13.0378V6.9445C13.0378 6.1142 13.2687 5.548 14.46 5.548H15.9781V2.983C15.2429 2.90401 14.5039 2.86595 13.7646 2.869C11.5758 2.869 10.0776 4.20565 10.0776 6.6595V8.77515H7.6V11.6423H10.0767V19H1.0488C0.4693 19 0 18.5307 0 17.9512V1.0488C0 0.4693 0.4693 0 1.0488 0H17.9512C18.5307 0 19 0.4693 19 1.0488V17.9512C19 18.5307 18.5307 19 17.9512 19H13.0388Z" fill="white"/>
								</svg>
							</a>
						</li>
						<li>
							<a href="https://www.instagram.com/accreditedsafetysolutions/" target="_blank" title="Instagram">
								<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M4.28607 0C3.14962 -8.37989e-08 2.05969 0.45134 1.25594 1.25478C0.452195 2.05822 0.000436425 3.14797 0 4.28442V15.7139C0 16.8507 0.451566 17.9408 1.25536 18.7446C2.05915 19.5484 3.14933 20 4.28607 20H15.7156C16.852 19.9996 17.9418 19.5478 18.7452 18.7441C19.5487 17.9403 20 16.8504 20 15.7139V4.28442C19.9996 3.14826 19.548 2.05875 18.7446 1.25536C17.9412 0.451969 16.8517 0.000436232 15.7156 0H4.28607ZM16.9484 4.29101C16.9484 4.61841 16.8183 4.9324 16.5868 5.1639C16.3553 5.39541 16.0413 5.52547 15.7139 5.52547C15.3865 5.52547 15.0725 5.39541 14.841 5.1639C14.6095 4.9324 14.4795 4.61841 14.4795 4.29101C14.4795 3.9636 14.6095 3.64961 14.841 3.41811C15.0725 3.1866 15.3865 3.05654 15.7139 3.05654C16.0413 3.05654 16.3553 3.1866 16.5868 3.41811C16.8183 3.64961 16.9484 3.9636 16.9484 4.29101ZM10.0025 6.57559C9.09448 6.57559 8.22368 6.93629 7.58163 7.57834C6.93958 8.22038 6.57888 9.09119 6.57888 9.99918C6.57888 10.9072 6.93958 11.778 7.58163 12.42C8.22368 13.0621 9.09448 13.4228 10.0025 13.4228C10.9105 13.4228 11.7813 13.0621 12.4233 12.42C13.0654 11.778 13.4261 10.9072 13.4261 9.99918C13.4261 9.09119 13.0654 8.22038 12.4233 7.57834C11.7813 6.93629 10.9105 6.57559 10.0025 6.57559ZM4.93128 9.99918C4.93128 8.65465 5.46539 7.36519 6.41612 6.41447C7.36684 5.46375 8.6563 4.92964 10.0008 4.92964C11.3453 4.92964 12.6348 5.46375 13.5855 6.41447C14.5363 7.36519 15.0704 8.65465 15.0704 9.99918C15.0704 11.3437 14.5363 12.6332 13.5855 13.5839C12.6348 14.5346 11.3453 15.0687 10.0008 15.0687C8.6563 15.0687 7.36684 14.5346 6.41612 13.5839C5.46539 12.6332 4.93128 11.3437 4.93128 9.99918Z" fill="white"/>
								</svg>
							</a>
						</li>
						<li>
							<a href="https://www.linkedin.com/company/accredited-safety-solutions-inc" target="_blank" title="LinkedIn">
								<svg width="19" height="19" viewBox="0 0 19 19" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path d="M16.8889 0C17.4488 0 17.9858 0.22242 18.3817 0.61833C18.7776 1.01424 19 1.55121 19 2.11111V16.8889C19 17.4488 18.7776 17.9858 18.3817 18.3817C17.9858 18.7776 17.4488 19 16.8889 19H2.11111C1.55121 19 1.01424 18.7776 0.61833 18.3817C0.22242 17.9858 0 17.4488 0 16.8889V2.11111C0 1.55121 0.22242 1.01424 0.61833 0.61833C1.01424 0.22242 1.55121 0 2.11111 0H16.8889ZM16.3611 16.3611V10.7667C16.3611 9.85403 15.9986 8.97877 15.3532 8.33343C14.7079 7.6881 13.8326 7.32556 12.92 7.32556C12.0228 7.32556 10.9778 7.87444 10.4711 8.69778V7.52611H7.52611V16.3611H10.4711V11.1572C10.4711 10.3444 11.1256 9.67944 11.9383 9.67944C12.3303 9.67944 12.7061 9.83514 12.9833 10.1123C13.2604 10.3894 13.4161 10.7653 13.4161 11.1572V16.3611H16.3611ZM4.09556 5.86889C4.56587 5.86889 5.01693 5.68206 5.34949 5.34949C5.68206 5.01693 5.86889 4.56587 5.86889 4.09556C5.86889 3.11389 5.07722 2.31167 4.09556 2.31167C3.62244 2.31167 3.1687 2.49961 2.83416 2.83416C2.49961 3.1687 2.31167 3.62244 2.31167 4.09556C2.31167 5.07722 3.11389 5.86889 4.09556 5.86889ZM5.56278 16.3611V7.52611H2.63889V16.3611H5.56278Z" fill="white"/>
								</svg>
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
			</div>

			<div>
				
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

<?php wp_footer(); ?>
</body>
</html>
