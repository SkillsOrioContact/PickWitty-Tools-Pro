<?php
/**
 * PickWitty Tools Pro Theme functions and definitions
 *
 * @package PickWitty_Tools_Pro
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PW_TOOLS_VERSION', '1.0.0' );
define( 'PW_TOOLS_DIR', get_template_directory() );
define( 'PW_TOOLS_URI', get_template_directory_uri() );

/**
 * Setup theme defaults and registers support for various WordPress features.
 */
function pw_tools_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'tool-card', 600, 400, true );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Header Menu', 'pickwitty-tools-pro' ),
			'footer'  => esc_html__( 'Footer Menu', 'pickwitty-tools-pro' ),
		)
	);

	// HTML5 markup support.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Custom logo support.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'pw_tools_setup' );

/**
 * Register widget area.
 */
function pw_tools_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'pickwitty-tools-pro' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in sidebar.', 'pickwitty-tools-pro' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'pw_tools_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function pw_tools_scripts() {
	wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'pw-tools-variables', PW_TOOLS_URI . '/assets/css/variables.css', array(), PW_TOOLS_VERSION );
	wp_enqueue_style( 'pw-tools-main', PW_TOOLS_URI . '/assets/css/main.css', array( 'pw-tools-variables' ), PW_TOOLS_VERSION );
	wp_enqueue_style( 'pw-tools-style', get_stylesheet_uri(), array( 'pw-tools-main' ), PW_TOOLS_VERSION );

	if ( is_singular( 'tool' ) ) {
		wp_enqueue_style( 'pw-tools-single', PW_TOOLS_URI . '/assets/css/tool-single.css', array( 'pw-tools-main' ), PW_TOOLS_VERSION );
	}

	wp_enqueue_script( 'pw-tools-main-js', PW_TOOLS_URI . '/assets/js/main.js', array(), PW_TOOLS_VERSION, true );

	wp_localize_script(
		'pw-tools-main-js',
		'pwToolsData',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'pw_tools_nonce' ),
		)
	);

	if ( is_home() || is_archive() || is_front_page() ) {
		wp_enqueue_script( 'pw-tools-infinite-scroll', PW_TOOLS_URI . '/assets/js/infinite-scroll.js', array( 'pw-tools-main-js' ), PW_TOOLS_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'pw_tools_scripts' );

/**
 * Required Include Files
 */
require_once PW_TOOLS_DIR . '/inc/cpt-tools.php';
require_once PW_TOOLS_DIR . '/inc/customizer.php';
require_once PW_TOOLS_DIR . '/inc/seo-schema.php';
require_once PW_TOOLS_DIR . '/inc/ajax-handlers.php';
require_once PW_TOOLS_DIR . '/inc/theme-setup.php';
