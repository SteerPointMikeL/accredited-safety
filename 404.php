<?php
/**
 * 404 template.
 *
 * @package ACCR_Theme
 */
get_header();
?>
<section class="page-hero">
	<div class="container">
		<span class="page-hero__eyebrow">404</span>
		<h1 class="page-hero__title">Page not found.</h1>
		<p class="page-hero__lead">The page you were looking for has moved or doesn't exist. Try the navigation above, or head back home.</p>
	</div>
</section>
<section class="section section--tight">
	<div class="container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary btn--lg">Back to home</a>
	</div>
</section>
<?php
get_footer();
