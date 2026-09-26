<?php
/**
 * Register Custom Post Type 'tool' and Taxonomy 'tool_category'
 * Meta Boxes for tool functionality & code injection
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Tool Custom Post Type
 */
function pw_tools_register_cpt() {
	$labels = array(
		'name'                  => _x( 'Tools', 'Post Type General Name', 'pickwitty-tools-pro' ),
		'singular_name'         => _x( 'Tool', 'Post Type Singular Name', 'pickwitty-tools-pro' ),
		'menu_name'             => __( 'Tools Pro', 'pickwitty-tools-pro' ),
		'name_admin_bar'        => __( 'Tool', 'pickwitty-tools-pro' ),
		'archives'              => __( 'Tool Archives', 'pickwitty-tools-pro' ),
		'attributes'            => __( 'Tool Attributes', 'pickwitty-tools-pro' ),
		'parent_item_colon'     => __( 'Parent Tool:', 'pickwitty-tools-pro' ),
		'all_items'             => __( 'All Tools', 'pickwitty-tools-pro' ),
		'add_new_item'          => __( 'Add New Tool', 'pickwitty-tools-pro' ),
		'add_new'               => __( 'Add Tool', 'pickwitty-tools-pro' ),
		'new_item'              => __( 'New Tool', 'pickwitty-tools-pro' ),
		'edit_item'             => __( 'Edit Tool', 'pickwitty-tools-pro' ),
		'update_item'           => __( 'Update Tool', 'pickwitty-tools-pro' ),
		'view_item'             => __( 'View Tool', 'pickwitty-tools-pro' ),
		'view_items'            => __( 'View Tools', 'pickwitty-tools-pro' ),
		'search_items'          => __( 'Search Tool', 'pickwitty-tools-pro' ),
		'not_found'             => __( 'Not found', 'pickwitty-tools-pro' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'pickwitty-tools-pro' ),
		'featured_image'        => __( 'Tool Featured Image (Recommended: 600x400px PNG/JPG)', 'pickwitty-tools-pro' ),
		'set_featured_image'    => __( 'Set featured image', 'pickwitty-tools-pro' ),
		'remove_featured_image' => __( 'Remove featured image', 'pickwitty-tools-pro' ),
		'use_featured_image'    => __( 'Use as featured image', 'pickwitty-tools-pro' ),
	);

	$args = array(
		'label'               => __( 'Tool', 'pickwitty-tools-pro' ),
		'description'         => __( 'Software utilities and online tools', 'pickwitty-tools-pro' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'taxonomies'          => array( 'tool_category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-hammer',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'tools',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);

	register_post_type( 'tool', $args );
}
add_action( 'init', 'pw_tools_register_cpt', 0 );

/**
 * Register Taxonomy Tool Category
 */
function pw_tools_register_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Tool Categories', 'Taxonomy General Name', 'pickwitty-tools-pro' ),
		'singular_name'              => _x( 'Tool Category', 'Taxonomy Singular Name', 'pickwitty-tools-pro' ),
		'menu_name'                  => __( 'Tool Categories', 'pickwitty-tools-pro' ),
		'all_items'                  => __( 'All Categories', 'pickwitty-tools-pro' ),
		'parent_item'                => __( 'Parent Category', 'pickwitty-tools-pro' ),
		'parent_item_colon'          => __( 'Parent Category:', 'pickwitty-tools-pro' ),
		'new_item_name'              => __( 'New Category Name', 'pickwitty-tools-pro' ),
		'add_new_item'               => __( 'Add New Tool Category', 'pickwitty-tools-pro' ),
		'edit_item'                  => __( 'Edit Tool Category', 'pickwitty-tools-pro' ),
		'update_item'                => __( 'Update Tool Category', 'pickwitty-tools-pro' ),
		'view_item'                  => __( 'View Category', 'pickwitty-tools-pro' ),
		'separate_items_with_commas' => __( 'Separate categories with commas', 'pickwitty-tools-pro' ),
		'add_or_remove_items'        => __( 'Add or remove categories', 'pickwitty-tools-pro' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'pickwitty-tools-pro' ),
		'popular_items'              => __( 'Popular Categories', 'pickwitty-tools-pro' ),
		'search_items'               => __( 'Search Categories', 'pickwitty-tools-pro' ),
		'not_found'                  => __( 'Not Found', 'pickwitty-tools-pro' ),
		'no_terms'                   => __( 'No categories', 'pickwitty-tools-pro' ),
		'items_list'                 => __( 'Categories list', 'pickwitty-tools-pro' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'tool-category' ),
	);

	register_taxonomy( 'tool_category', array( 'tool' ), $args );
}
add_action( 'init', 'pw_tools_register_taxonomy', 0 );

/**
 * Add Tool Meta Boxes
 */
function pw_tools_add_meta_boxes() {
	add_meta_box(
		'pw_tool_details_meta',
		__( 'Tool Configuration & Functional Code Area', 'pickwitty-tools-pro' ),
		'pw_tools_meta_box_callback',
		'tool',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'pw_tools_add_meta_boxes' );

/**
 * Meta Box Callback Function
 */
function pw_tools_meta_box_callback( $post ) {
	wp_nonce_field( 'pw_tools_save_meta', 'pw_tools_meta_nonce' );

	$short_desc = get_post_meta( $post->ID, '_pw_tool_short_desc', true );
	$icon       = get_post_meta( $post->ID, '_pw_tool_icon', true );
	$html_code  = get_post_meta( $post->ID, '_pw_tool_html', true );
	$css_code   = get_post_meta( $post->ID, '_pw_tool_css', true );
	$js_code    = get_post_meta( $post->ID, '_pw_tool_js', true );
	?>
	<style>
		.pw-meta-field { margin-bottom: 20px; }
		.pw-meta-field label { font-weight: 600; display: block; margin-bottom: 6px; font-size: 14px; }
		.pw-meta-field input[type="text"], .pw-meta-field textarea { width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 8px 12px; font-family: monospace; font-size: 13px; }
		.pw-meta-desc { font-size: 12px; color: #666; margin-top: 4px; }
		.pw-code-textarea { min-height: 140px; background: #282c34; color: #abb2bf; line-height: 1.5; }
	</style>

	<div class="pw-meta-field">
		<label for="pw_tool_icon"><?php esc_html_e( 'Tool Icon / Emoji:', 'pickwitty-tools-pro' ); ?></label>
		<input type="text" id="pw_tool_icon" name="pw_tool_icon" value="<?php echo esc_attr( $icon ); ?>" placeholder="e.g. ⚡, 🖼️, 📱, or SVG code" />
		<p class="pw-meta-desc"><?php esc_html_e( 'Displayed on cards, breadcrumbs, and headers.', 'pickwitty-tools-pro' ); ?></p>
	</div>

	<div class="pw-meta-field">
		<label for="pw_tool_short_desc"><?php esc_html_e( 'Short Description:', 'pickwitty-tools-pro' ); ?></label>
		<input type="text" id="pw_tool_short_desc" name="pw_tool_short_desc" value="<?php echo esc_attr( $short_desc ); ?>" placeholder="<?php esc_attr_e( 'Brief overview used on cards, breadcrumbs, and search excerpts...', 'pickwitty-tools-pro' ); ?>" />
		<p class="pw-meta-desc"><?php esc_html_e( 'Concise 1-2 sentence description for tool cards and breadcrumbs.', 'pickwitty-tools-pro' ); ?></p>
	</div>

	<hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

	<div class="pw-meta-field">
		<label for="pw_tool_html"><?php esc_html_e( 'Custom Tool HTML Code:', 'pickwitty-tools-pro' ); ?></label>
		<textarea id="pw_tool_html" name="pw_tool_html" class="pw-code-textarea" rows="8" placeholder="<div class='my-tool-wrapper'>...</div>"><?php echo esc_textarea( $html_code ); ?></textarea>
		<p class="pw-meta-desc"><?php esc_html_e( 'The HTML layout for the functional SaaS utility (buttons, input fields, file drop zones, canvas elements).', 'pickwitty-tools-pro' ); ?></p>
	</div>

	<div class="pw-meta-field">
		<label for="pw_tool_css"><?php esc_html_e( 'Custom Tool CSS Code:', 'pickwitty-tools-pro' ); ?></label>
		<textarea id="pw_tool_css" name="pw_tool_css" class="pw-code-textarea" rows="8" placeholder=".my-tool-wrapper { background: #fff; padding: 20px; }"><?php echo esc_textarea( $css_code ); ?></textarea>
		<p class="pw-meta-desc"><?php esc_html_e( 'Styles specific to this tool. Automatically scoped and injected on the tool page.', 'pickwitty-tools-pro' ); ?></p>
	</div>

	<div class="pw-meta-field">
		<label for="pw_tool_js"><?php esc_html_e( 'Custom Tool JavaScript Code:', 'pickwitty-tools-pro' ); ?></label>
		<textarea id="pw_tool_js" name="pw_tool_js" class="pw-code-textarea" rows="10" placeholder="document.addEventListener('DOMContentLoaded', function() { ... });"><?php echo esc_textarea( $js_code ); ?></textarea>
		<p class="pw-meta-desc"><?php esc_html_e( 'JavaScript logic powering this SaaS tool (client-side processing, canvas manipulations, file generation, etc.). Executed safely on page load.', 'pickwitty-tools-pro' ); ?></p>
	</div>

	<div class="notice notice-info inline" style="margin-top: 15px; padding: 10px;">
		<p><strong><?php esc_html_e( 'Featured Image Recommendation:', 'pickwitty-tools-pro' ); ?></strong> <?php esc_html_e( 'Upload an image with 600x400px aspect ratio for high resolution tool cards. If no featured image is set, a sleek gradient background overlay with tool title and icon will automatically be rendered.', 'pickwitty-tools-pro' ); ?></p>
	</div>
	<?php
}

/**
 * Save Tool Meta Box Data
 */
function pw_tools_save_meta( $post_id ) {
	if ( ! isset( $_POST['pw_tools_meta_nonce'] ) || ! wp_verify_nonce( $_POST['pw_tools_meta_nonce'], 'pw_tools_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['pw_tool_short_desc'] ) ) {
		update_post_meta( $post_id, '_pw_tool_short_desc', sanitize_text_field( $_POST['pw_tool_short_desc'] ) );
	}

	if ( isset( $_POST['pw_tool_icon'] ) ) {
		update_post_meta( $post_id, '_pw_tool_icon', sanitize_text_field( $_POST['pw_tool_icon'] ) );
	}

	// Code fields allowing raw HTML, CSS, JS
	if ( isset( $_POST['pw_tool_html'] ) ) {
		$clean_html = pw_tools_strip_structural_elements( $_POST['pw_tool_html'] );
		update_post_meta( $post_id, '_pw_tool_html', $clean_html );
	}

	if ( isset( $_POST['pw_tool_css'] ) ) {
		update_post_meta( $post_id, '_pw_tool_css', $_POST['pw_tool_css'] );
	}

	if ( isset( $_POST['pw_tool_js'] ) ) {
		update_post_meta( $post_id, '_pw_tool_js', $_POST['pw_tool_js'] );
	}
}
add_action( 'save_post_tool', 'pw_tools_save_meta' );

/**
 * Utility Function: Strip structural layout elements (<header>, <footer>, <nav>, <aside>) from Tool Code input.
 */
function pw_tools_strip_structural_elements( $html ) {
	if ( empty( $html ) ) {
		return '';
	}

	// Remove structural wrapper tags
	$patterns = array(
		'/<header[\s\S]*?<\/header>/i',
		'/<footer[\s\S]*?<\/footer>/i',
		'/<nav[\s\S]*?<\/nav>/i',
		'/<aside[\s\S]*?<\/aside>/i',
	);

	return preg_replace( $patterns, '', $html );
}

/**
 * Output Tool Inline CSS and JS on Single Tool Page
 */
function pw_tools_render_tool_custom_code() {
	if ( is_singular( 'tool' ) ) {
		global $post;
		$css_code = get_post_meta( $post->ID, '_pw_tool_css', true );
		$js_code  = get_post_meta( $post->ID, '_pw_tool_js', true );

		if ( ! empty( $css_code ) ) {
			echo '<style id="pw-tool-custom-css">' . $css_code . '</style>' . "\n";
		}

		if ( ! empty( $js_code ) ) {
			add_action(
				'wp_footer',
				function() use ( $js_code ) {
					echo '<script id="pw-tool-custom-js">' . $js_code . '</script>' . "\n";
				},
				99
			);
		}
	}
}
add_action( 'wp_head', 'pw_tools_render_tool_custom_code' );
