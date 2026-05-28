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
	<?php /* <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%230F2A3D'/%3E%3Cpath d='M8 22 L16 8 L24 22 Z' fill='none' stroke='%23EE6A19' stroke-width='2.5' stroke-linejoin='round'/%3E%3Ccircle cx='16' cy='18' r='2' fill='%23EE6A19'/%3E%3C/svg%3E" /> */ ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="sr-only">Skip to main content</a>

<!-- Announcement bar -->
<div class="announcement">
	<div class="container announcement__inner">
		<?php
		//$phone_display = get_theme_mod( 'accr_phone_display', '844-717-3665' );
		$phone_link    = get_theme_mod( 'accr_phone_link', 'tel:8447173665' );
		$email         = get_theme_mod( 'accr_email', 'info@accredited-safety.com' );
		?>
		<?php /* <a href="<?php echo esc_attr( $phone_link ); ?>" aria-label="Call us">
			<?php echo accr_icon( 'phone', array( 'width' => '14', 'height' => '14' ) ); ?>
			<?php echo esc_html( $phone_display ); ?>
		</a> */ ?>
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
					'depth'          => 1,
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
			<button class="theme-toggle" data-theme-toggle aria-label="Toggle color theme"></button>
			<a href="<?php echo esc_attr( $phone_link ); ?>" class="btn btn--primary">Call: 844-484-9628</a>
			<button class="nav-toggle" data-nav-toggle aria-label="Open menu" aria-expanded="false">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
			</button>
		</div>
	</div>
</header>

<main id="main">
