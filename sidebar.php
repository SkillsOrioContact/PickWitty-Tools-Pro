<?php
/**
 * Sidebar Template
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! is_active_sidebar( 'sidebar-1' ) && ! function_exists( 'pw_tools_render_ad' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area sidebar-container">
	<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
		<?php pw_tools_render_ad( 'sidebar' ); ?>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php else : ?>
		<section class="widget widget_tools_list">
			<h3 class="widget-title"><?php esc_html_e( 'Featured Utilities', 'pickwitty-tools-pro' ); ?></h3>
			<ul class="widget-tool-items">
				<?php
				$recent_tools = get_posts(
					array(
						'post_type'      => 'tool',
						'posts_per_page' => 5,
					)
				);
				foreach ( $recent_tools as $tool_item ) :
					$short_desc = get_post_meta( $tool_item->ID, '_pw_tool_short_desc', true );
					$icon       = get_post_meta( $tool_item->ID, '_pw_tool_icon', true ) ?: '⚡';
					?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $tool_item->ID ) ); ?>" class="sidebar-tool-link">
							<span class="tool-icon"><?php echo esc_html( $icon ); ?></span>
							<div>
								<strong><?php echo esc_html( $tool_item->post_title ); ?></strong>
								<?php if ( $short_desc ) : ?>
									<small><?php echo esc_html( wp_trim_words( $short_desc, 6 ) ); ?></small>
								<?php endif; ?>
							</div>
						</a>
					</li>
				<?php endforeach; wp_reset_postdata(); ?>
			</ul>
		</section>
	<?php endif; ?>
</aside>
