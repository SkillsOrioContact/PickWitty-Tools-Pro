	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<!-- Footer Ad Banner if configured -->
		<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
			<?php pw_tools_render_ad( 'footer' ); ?>
		<?php endif; ?>

		<div class="footer-widgets-area">
			<div class="container footer-grid">
				<div class="footer-branding">
					<div class="site-branding">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
							<div class="brand-logo-icon">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<span class="site-title"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>
					<p class="footer-desc"><?php echo esc_html( get_theme_mod( 'pw_footer_desc', __( 'PickWitty Tools Pro is a suite of free, high-performance web software utilities and tools designed for privacy, speed, and efficiency.', 'pickwitty-tools-pro' ) ) ); ?></p>
				</div>

				<div class="footer-links-col">
					<h4 class="footer-heading"><?php esc_html_e( 'Quick Links', 'pickwitty-tools-pro' ); ?></h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
							'fallback_cb'    => 'pw_tools_default_footer_menu',
						)
					);
					?>
				</div>

				<div class="footer-links-col">
					<h4 class="footer-heading"><?php esc_html_e( 'Popular Tools', 'pickwitty-tools-pro' ); ?></h4>
					<ul class="footer-menu">
						<?php
						$popular_tools = get_posts(
							array(
								'post_type'      => 'tool',
								'posts_per_page' => 5,
								'orderby'        => 'title',
								'order'          => 'ASC',
							)
						);
						foreach ( $popular_tools as $tool ) :
							?>
							<li><a href="<?php echo esc_url( get_permalink( $tool->ID ) ); ?>"><?php echo esc_html( $tool->post_title ); ?></a></li>
						<?php endforeach; wp_reset_postdata(); ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="site-info-bar">
			<div class="container site-info-content">
				<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'pickwitty-tools-pro' ); ?></p>
				<p class="author-credit"><?php esc_html_e( 'Designed by', 'pickwitty-tools-pro' ); ?> <a href="https://pickwitty.com" target="_blank" rel="noopener">PickWitty</a></p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
