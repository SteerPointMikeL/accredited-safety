<?php
/**
 * Site header.
 *
 * @package ACCR_Theme
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	
	<!-- wp_head() -->
	<?php wp_head(); ?>
	<!-- end wp_head() -->
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="sr-only">Skip to main content</a>

<!-- Announcement bar -->
<div class="announcement">
	<div class="container announcement__inner">
		<?php
		$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8447173665' );
		$email         = get_theme_mod( 'accr_email', 'info@accredited-safety.com' );
		?>
		<a href="mailto:<?php echo esc_attr( $email ); ?>">
			<?php echo accr_icon( 'mail', array( 'width' => '14', 'height' => '14' ) ); ?>
			<?php echo esc_html( strtoupper( $email ) ); ?>
		</a>
	</div>
</div>

<!-- Header -->
<header class="site-header">
	<div class="container nav">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — home">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/header-logo.webp" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="275" height="54" />
		</a>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'nav-links',
					'container'      => false,
					'items_wrap'     => '<ul class="%2$s" data-nav-links role="list">%3$s</ul>',
					'depth'          => 0,
					'walker'         => new ACCR_Primary_Nav_Walker(),
				)
			);
			?>
		<?php else : ?>
			<ul class="nav-links" data-nav-links role="list">
				<li><a href="<?php echo esc_url( home_url( '/certifications/' ) ); ?>">Certifications</a></li>
				<li><a href="<?php echo esc_url( home_url( '/classes/' ) ); ?>">Classes</a></li>
				<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
			</ul>
		<?php endif; ?>

		<div class="nav-actions">
			<a href="<?php echo esc_attr( $phone_link ); ?>" class="btn btn--primary">Call: 844-484-9628</a>
			<button class="nav-toggle" data-nav-toggle aria-label="Open menu" aria-expanded="false">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
			</button>
		</div>
	</div>
</header>

<main id="main">
