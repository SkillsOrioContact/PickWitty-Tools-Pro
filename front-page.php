<?php
/**
 * Front Page Template (Homepage for PickWitty Tools Pro)
 * Lightning-fast modern UI with Hero banner, live instant search, category tabs,
 * tools grid with ad card insertion, and recent blogs section.
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<!-- Hero Banner Section -->
<section class="hero-section">
	<div class="container hero-content">
		<div class="hero-badge">
			<span>⚡</span> <?php esc_html_e( '100% Free & Browser-Based SaaS Utilities', 'pickwitty-tools-pro' ); ?>
		</div>
		<h1 class="hero-title">
			<?php echo esc_html( get_theme_mod( 'pw_hero_title', __( '100+ Free Online Software Utilities & Micro Tools', 'pickwitty-tools-pro' ) ) ); ?>
		</h1>
		<p class="hero-subtitle">
			<?php echo esc_html( get_theme_mod( 'pw_hero_subtitle', __( 'Fast, private, browser-based tools with zero installations or downloads required. Everything runs locally in your web browser.', 'pickwitty-tools-pro' ) ) ); ?>
		</p>

		<!-- Instant Search Bar -->
		<div class="tools-search-box">
			<svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
			<input type="text" id="pw-tools-search" class="tools-search-input" placeholder="<?php esc_attr_e( 'Search tools (e.g. image compressor, qr generator)...', 'pickwitty-tools-pro' ); ?>">
		</div>

		<!-- Category Filter Tabs -->
		<div class="category-filter-nav">
			<button class="cat-tab active" data-cat="all"><?php esc_html_e( 'All Utilities', 'pickwitty-tools-pro' ); ?></button>
			<?php
			$categories = get_terms(
				array(
					'taxonomy'   => 'tool_category',
					'hide_empty' => true,
				)
			);
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
				foreach ( $categories as $cat ) :
					?>
					<button class="cat-tab" data-cat="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
					<?php
				endforeach;
			endif;
			?>
		</div>
	</div>
</section>

<!-- Main Tools Grid Section -->
<main id="primary" class="site-main container py-8">
	<!-- Header Ad Banner Slot -->
	<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
		<?php pw_tools_render_ad( 'header' ); ?>
	<?php endif; ?>

	<div class="section-title-bar flex-between mb-6">
		<h2 class="text-2xl font-extrabold"><?php esc_html_e( 'Featured Software Utilities', 'pickwitty-tools-pro' ); ?></h2>
	</div>

	<div class="tools-grid" id="pw-tools-grid">
		<?php
		$tools_query = new WP_Query(
			array(
				'post_type'      => 'tool',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		if ( $tools_query->have_posts() ) :
			$tool_count   = 0;
			$ad_frequency = (int) get_theme_mod( 'pw_adsense_card_freq', 3 );

			while ( $tools_query->have_posts() ) :
				$tools_query->the_post();
				$tool_count++;

				$short_desc = get_post_meta( get_the_ID(), '_pw_tool_short_desc', true ) ?: get_the_excerpt();
				$tool_icon  = get_post_meta( get_the_ID(), '_pw_tool_icon', true ) ?: '⚡';
				$tool_cats  = wp_get_post_terms( get_the_ID(), 'tool_category', array( 'fields' => 'slugs' ) );
				$cat_string = is_array( $tool_cats ) ? implode( ' ', $tool_cats ) : '';
				?>
				<article class="tool-card" data-title="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>" data-desc="<?php echo esc_attr( strtolower( $short_desc ) ); ?>" data-categories="<?php echo esc_attr( $cat_string ); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="tool-card-media">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'tool-card', array( 'class' => 'tool-card-img', 'alt' => get_the_title() ) ); ?>
							</a>
						</div>
					<?php else : ?>
						<div class="tool-card-media tool-card-gradient">
							<div class="text-center">
								<div class="tool-card-icon"><?php echo esc_html( $tool_icon ); ?></div>
							</div>
						</div>
					<?php endif; ?>

					<div class="tool-card-body">
						<h3 class="tool-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<p class="tool-card-desc"><?php echo esc_html( wp_trim_words( $short_desc, 18 ) ); ?></p>
						<div class="tool-card-footer">
							<a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
								<?php esc_html_e( 'Open Tool', 'pickwitty-tools-pro' ); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
							</a>
						</div>
					</div>
				</article>

				<?php
				// Insert Ad Card periodically if enabled
				if ( $ad_frequency > 0 && $tool_count % $ad_frequency === 0 && function_exists( 'pw_tools_render_ad_card' ) ) {
					pw_tools_render_ad_card();
				}
				endwhile;
			wp_reset_postdata();
		else :
			?>
			<div class="no-tools-found card-box text-center py-12" style="grid-column: 1 / -1;">
				<h3><?php esc_html_e( 'No Software Tools Published Yet', 'pickwitty-tools-pro' ); ?></h3>
				<p><?php esc_html_e( 'Go to WordPress Admin -> Tools Pro -> Add Tool to publish your first SaaS utility.', 'pickwitty-tools-pro' ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<!-- Recent Blogs Section -->
	<?php
	$recent_count = (int) get_theme_mod( 'pw_recent_blogs_count', 3 );
	if ( $recent_count > 0 ) :
		$blog_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => $recent_count,
				'post_status'    => 'publish',
			)
		);

		if ( $blog_query->have_posts() ) :
			?>
			<section class="recent-blogs-section mt-12 card-box">
				<div class="flex-between mb-6">
					<div>
						<h2 class="text-2xl font-extrabold"><?php esc_html_e( 'Recent Blogs & Articles', 'pickwitty-tools-pro' ); ?></h2>
						<p class="text-secondary"><?php esc_html_e( 'Tips, updates, and tutorials on digital software tools and web efficiency.', 'pickwitty-tools-pro' ); ?></p>
					</div>
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-secondary btn-sm"><?php esc_html_e( 'View All Posts', 'pickwitty-tools-pro' ); ?></a>
				</div>

				<div class="blog-grid">
					<?php
					while ( $blog_query->have_posts() ) :
						$blog_query->the_post();
						?>
						<article class="blog-card">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="blog-card-media">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium', array( 'class' => 'blog-card-img', 'alt' => get_the_title() ) ); ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="blog-card-body">
								<div class="blog-card-meta">
									<span><?php echo esc_html( get_the_date() ); ?></span>
								</div>
								<h3 class="blog-card-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
								<div class="blog-card-excerpt">
									<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?></p>
								</div>
							</div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</section>
			<?php
		endif;
	endif;
	?>
</main>

<?php
get_footer();
