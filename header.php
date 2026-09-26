<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'pickwitty-tools-pro' ); ?></a>

	<!-- Header Ad Banner if configured -->
	<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
		<?php pw_tools_render_ad( 'header' ); ?>
	<?php endif; ?>

	<header id="masthead" class="site-header">
		<div class="header-container container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
						<div class="brand-logo-icon">
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
						<div class="brand-text">
							<span class="site-title"><?php bloginfo( 'name' ); ?></span>
							<span class="site-tagline"><?php bloginfo( 'description' ); ?></span>
						</div>
					</a>
					<?php
				endif;
				?>
			</div>

			<nav id="site-navigation" class="main-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle-icon"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'pickwitty-tools-pro' ); ?></span>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'menu_class'     => 'nav-menu',
						'fallback_cb'    => 'pw_tools_default_menu',
					)
				);
				?>
			</nav>

			<div class="header-actions">
				<?php if ( get_theme_mod( 'pw_enable_dark_mode_toggle', true ) ) : ?>
					<button id="theme-toggle-btn" class="theme-toggle-btn" aria-label="<?php esc_attr_e( 'Toggle Light/Dark Theme', 'pickwitty-tools-pro' ); ?>" title="<?php esc_attr_e( 'Toggle Theme Mode', 'pickwitty-tools-pro' ); ?>">
						<svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
						<svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
					</button>
				<?php endif; ?>

				<a href="<?php echo esc_url( get_post_type_archive_link( 'tool' ) ); ?>" class="btn btn-primary btn-sm">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
					<?php esc_html_e( 'Browse Tools', 'pickwitty-tools-pro' ); ?>
				</a>
			</div>
		</div>
	</header>

	<div id="content" class="site-content">
