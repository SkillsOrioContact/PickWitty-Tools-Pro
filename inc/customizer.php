<?php
/**
 * PickWitty Tools Pro Theme Customizer API & AdSense Settings
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pw_tools_customize_register( $wp_customize ) {

	// 1. Branding & Color Options Panel
	$wp_customize->add_section(
		'pw_color_options',
		array(
			'title'       => __( 'Branding & Theme Colors', 'pickwitty-tools-pro' ),
			'priority'    => 20,
			'description' => __( 'Customize primary accent colors and UI themes.', 'pickwitty-tools-pro' ),
		)
	);

	// Primary Accent Color
	$wp_customize->add_setting(
		'pw_primary_color',
		array(
			'default'           => '#6366f1', // Vibrant Indigo
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pw_primary_color',
			array(
				'label'    => __( 'Primary Accent Color', 'pickwitty-tools-pro' ),
				'section'  => 'pw_color_options',
				'settings' => 'pw_primary_color',
			)
		)
	);

	// Gradient Color 2
	$wp_customize->add_setting(
		'pw_secondary_color',
		array(
			'default'           => '#a855f7', // Vibrant Purple
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'pw_secondary_color',
			array(
				'label'    => __( 'Secondary Gradient Color', 'pickwitty-tools-pro' ),
				'section'  => 'pw_color_options',
				'settings' => 'pw_secondary_color',
			)
		)
	);

	// 2. Homepage Hero Section Options
	$wp_customize->add_section(
		'pw_hero_options',
		array(
			'title'       => __( 'Homepage Hero Section', 'pickwitty-tools-pro' ),
			'priority'    => 25,
			'description' => __( 'Customize the main homepage hero banner.', 'pickwitty-tools-pro' ),
		)
	);

	$wp_customize->add_setting(
		'pw_hero_title',
		array(
			'default'           => __( '100+ Free Online Software Utilities & Micro Tools', 'pickwitty-tools-pro' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pw_hero_title',
		array(
			'label'   => __( 'Hero Main Title', 'pickwitty-tools-pro' ),
			'section' => 'pw_hero_options',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'pw_hero_subtitle',
		array(
			'default'           => __( 'Fast, private, browser-based tools with zero installations or downloads required. Everything runs locally in your web browser.', 'pickwitty-tools-pro' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'pw_hero_subtitle',
		array(
			'label'   => __( 'Hero Subtitle', 'pickwitty-tools-pro' ),
			'section' => 'pw_hero_options',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'pw_recent_blogs_count',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'pw_recent_blogs_count',
		array(
			'label'       => __( 'Number of Recent Blog Posts on Homepage', 'pickwitty-tools-pro' ),
			'section'     => 'pw_hero_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 12,
				'step' => 1,
			),
		)
	);

	// 3. AdSense & Monitization Settings Section
	$wp_customize->add_section(
		'pw_adsense_options',
		array(
			'title'       => __( 'AdSense & Display Ad Placements', 'pickwitty-tools-pro' ),
			'priority'    => 30,
			'description' => __( 'Manage Google AdSense scripts and ad card placements. Placeholder boxes will display if ad code is empty.', 'pickwitty-tools-pro' ),
		)
	);

	// Header Ad Code
	$wp_customize->add_setting(
		'pw_adsense_header',
		array(
			'default'           => '',
			'sanitize_callback' => 'pw_tools_sanitize_raw_html',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_header',
		array(
			'label'       => __( 'Header Banner Ad Code (Top of Page)', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'textarea',
			'description' => __( 'HTML / JS script provided by Google AdSense.', 'pickwitty-tools-pro' ),
		)
	);

	// Middle Ad Code
	$wp_customize->add_setting(
		'pw_adsense_middle',
		array(
			'default'           => '',
			'sanitize_callback' => 'pw_tools_sanitize_raw_html',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_middle',
		array(
			'label'       => __( 'Middle / Article Ad Code', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'textarea',
		)
	);

	// Footer Ad Code
	$wp_customize->add_setting(
		'pw_adsense_footer',
		array(
			'default'           => '',
			'sanitize_callback' => 'pw_tools_sanitize_raw_html',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_footer',
		array(
			'label'       => __( 'Footer Banner Ad Code', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'textarea',
		)
	);

	// Sidebar Ad Code
	$wp_customize->add_setting(
		'pw_adsense_sidebar',
		array(
			'default'           => '',
			'sanitize_callback' => 'pw_tools_sanitize_raw_html',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_sidebar',
		array(
			'label'       => __( 'Sidebar Widget Ad Code', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'textarea',
		)
	);

	// Card Grid In-Feed Ad Code
	$wp_customize->add_setting(
		'pw_adsense_card',
		array(
			'default'           => '',
			'sanitize_callback' => 'pw_tools_sanitize_raw_html',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_card',
		array(
			'label'       => __( 'Tool & Blog Grid Ad Card Code', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'textarea',
			'description' => __( 'In-feed ad code that will be styled into tool grid cards.', 'pickwitty-tools-pro' ),
		)
	);

	// Ad Frequency Setting
	$wp_customize->add_setting(
		'pw_adsense_card_freq',
		array(
			'default'           => 3,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'pw_adsense_card_freq',
		array(
			'label'       => __( 'Ad Card Frequency in Grid (e.g., Every N cards)', 'pickwitty-tools-pro' ),
			'section'     => 'pw_adsense_options',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 10,
				'step' => 1,
			),
		)
	);
}
add_action( 'customize_register', 'pw_tools_customize_register' );

/**
 * Sanitize raw HTML/JS code for AdSense inputs.
 */
function pw_tools_sanitize_raw_html( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		return $input;
	}
	return wp_kses_post( $input );
}

/**
 * Output dynamic CSS variables for branding colors in head.
 */
function pw_tools_customizer_css() {
	$primary_color   = get_theme_mod( 'pw_primary_color', '#6366f1' );
	$secondary_color = get_theme_mod( 'pw_secondary_color', '#a855f7' );
	?>
	<style id="pw-customizer-dynamic-css">
		:root {
			--primary-color: <?php echo esc_attr( $primary_color ); ?>;
			--primary-hover: <?php echo esc_attr( $primary_color ); ?>dd;
			--secondary-color: <?php echo esc_attr( $secondary_color ); ?>;
			--gradient-brand: linear-gradient(135deg, <?php echo esc_attr( $primary_color ); ?> 0%, <?php echo esc_attr( $secondary_color ); ?> 100%);
		}
	</style>
	<?php
}
add_action( 'wp_head', 'pw_tools_customizer_css' );

/**
 * Helper function to render AdSense banner slots.
 */
function pw_tools_render_ad( $slot_name ) {
	$ad_code = get_theme_mod( 'pw_adsense_' . $slot_name, '' );
	echo '<div class="pw-ad-slot pw-ad-' . esc_attr( $slot_name ) . '">';
	if ( ! empty( $ad_code ) ) {
		echo $ad_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		// Placeholder Box when no ad code is set
		echo '<div class="pw-ad-placeholder">';
		echo '<span class="ad-badge-top">' . esc_html__( 'Ad', 'pickwitty-tools-pro' ) . '</span>';
		echo '<span>' . sprintf( esc_html__( 'AdSense Slot [%s] - Paste code in Customizer', 'pickwitty-tools-pro' ), esc_html( ucfirst( $slot_name ) ) ) . '</span>';
		echo '</div>';
	}
	echo '</div>';
}

/**
 * Helper function to render In-Grid Ad Card.
 */
function pw_tools_render_ad_card() {
	$ad_code = get_theme_mod( 'pw_adsense_card', '' );
	?>
	<div class="tool-card ad-card-item">
		<span class="ad-card-badge"><?php esc_html_e( 'Ad', 'pickwitty-tools-pro' ); ?></span>
		<div class="ad-card-inner">
			<?php if ( ! empty( $ad_code ) ) : ?>
				<?php echo $ad_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="ad-card-placeholder">
					<div class="ad-icon">📢</div>
					<h3><?php esc_html_e( 'Sponsored Ad Space', 'pickwitty-tools-pro' ); ?></h3>
					<p><?php esc_html_e( 'Insert Google AdSense infeed code in Theme Customizer.', 'pickwitty-tools-pro' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
