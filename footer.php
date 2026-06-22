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
					<li><a href="/wp-content/uploads/2026/06/Regulation-Statement.pdf" target="_blank">Workforce Regulation Statement</a></li>
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

<footer class="mobile-footer">
	<div class="mobile-footer__actions">
		<a class="btn btn--primary" href="<?php echo esc_attr( $phone_link ); ?>">
			<?php echo accr_icon( 'phone' ); ?>
			Call
		</a>
		<a class="btn btn--secondary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
			<?php echo accr_icon( 'mail' ); ?>
			Contact Us
		</a>
	</div>
</footer>

<!-- Request Pricing modal -->
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
			}
			?>
		</div>
	</div>
</div>

<!-- Newsletter sign-up modal. Opened by the dummy footer newsletter card. -->
<div class="modal-backdrop" id="newsletter-modal" data-modal="newsletter" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="newsletter-modal-title">
	<div class="modal">
		<div class="modal__head">
			<div></div>
			<button class="modal__close" data-modal-close aria-label="<?php esc_attr_e( 'Close', 'accr-theme' ); ?>">
				<?php echo accr_icon( 'close', array( 'width' => '18', 'height' => '18', 'stroke-width' => '2.5' ) ); ?>
			</button>
		</div>
		<div class="modal__body">
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

<?php /* <script type="text/javascript" src="//cdn.callrail.com/companies/797005176/cb4bc87ea578a6d2da43/12/swap.js"></script>

<!-- Start of HubSpot Embed Code -->
<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/45591717.js"></script>
<!-- End of HubSpot Embed Code --> */ ?>

</body>
</html>
