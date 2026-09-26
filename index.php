<?php
/**
 * Main Template File (Fallback / Blog Posts Archive)
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<div class="archive-header-banner">
	<div class="container">
		<h1 class="page-title">
			<?php
			if ( is_home() ) {
				echo esc_html( get_theme_mod( 'pw_blog_title', __( 'Latest Tech & Software Blog', 'pickwitty-tools-pro' ) ) );
			} elseif ( is_archive() ) {
				the_archive_title();
			} else {
				esc_html_e( 'Blog Posts', 'pickwitty-tools-pro' );
			}
			?>
		</h1>
		<p class="page-subtitle">
			<?php
			if ( is_home() ) {
				echo esc_html( get_theme_mod( 'pw_blog_subtitle', __( 'Explore insightful articles, guides, and tutorials on software tools and web utilities.', 'pickwitty-tools-pro' ) ) );
			} else {
				the_archive_description();
			}
			?>
		</p>
	</div>
</div>

<main id="primary" class="site-main container py-8">
	<!-- Date / Archive Filter Bar -->
	<div class="archive-filter-bar">
		<form method="get" id="pw-archive-filter-form" class="filter-form">
			<label for="pw-date-filter" class="filter-label"><?php esc_html_e( 'Filter By Date:', 'pickwitty-tools-pro' ); ?></label>
			<select name="pw_archive_year" id="pw-year-filter" class="filter-select">
				<option value=""><?php esc_html_e( 'Select Year', 'pickwitty-tools-pro' ); ?></option>
				<?php
				$years = get_posts(
					array(
						'post_type'      => 'post',
						'posts_per_page' => -1,
						'fields'         => 'ids',
					)
				);
				$year_list = array();
				foreach ( $years as $post_id ) {
					$y = get_the_date( 'Y', $post_id );
					if ( ! in_array( $y, $year_list, true ) ) {
						$year_list[] = $y;
						$selected   = ( isset( $_GET['pw_archive_year'] ) && $_GET['pw_archive_year'] === $y ) ? 'selected' : '';
						echo '<option value="' . esc_attr( $y ) . '" ' . $selected . '>' . esc_html( $y ) . '</option>';
					}
				}
				?>
			</select>

			<select name="pw_archive_month" id="pw-month-filter" class="filter-select">
				<option value=""><?php esc_html_e( 'Select Month', 'pickwitty-tools-pro' ); ?></option>
				<?php
				for ( $m = 1; $m <= 12; $m++ ) {
					$month_num = sprintf( '%02d', $m );
					$month_name = date_i18n( 'F', mktime( 0, 0, 0, $m, 10 ) );
					$selected   = ( isset( $_GET['pw_archive_month'] ) && $_GET['pw_archive_month'] === $month_num ) ? 'selected' : '';
					echo '<option value="' . esc_attr( $month_num ) . '" ' . $selected . '>' . esc_html( $month_name ) . '</option>';
				}
				?>
			</select>

			<button type="submit" class="btn btn-secondary btn-sm"><?php esc_html_e( 'Apply Filter', 'pickwitty-tools-pro' ); ?></button>
			<?php if ( isset( $_GET['pw_archive_year'] ) || isset( $_GET['pw_archive_month'] ) ) : ?>
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="btn btn-outline btn-sm"><?php esc_html_e( 'Reset', 'pickwitty-tools-pro' ); ?></a>
			<?php endif; ?>
		</form>
	</div>

	<div class="main-layout-grid">
		<div class="content-area">
			<?php if ( have_posts() ) : ?>
				<div class="blog-grid" id="pw-blog-grid">
					<?php
					$post_count = 0;
					$ad_frequency = (int) get_theme_mod( 'pw_adsense_card_freq', 3 );

					while ( have_posts() ) :
						the_post();
						$post_count++;
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="blog-card-media">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium', array( 'class' => 'blog-card-img', 'alt' => get_the_title() ) ); ?>
									</a>
								</div>
							<?php else : ?>
								<div class="blog-card-media blog-card-gradient">
									<span class="blog-card-title-overlay"><?php echo esc_html( get_the_title() ); ?></span>
								</div>
							<?php endif; ?>

							<div class="blog-card-body">
								<div class="blog-card-meta">
									<span class="meta-date"><?php echo esc_html( get_the_date() ); ?></span>
									<span class="meta-sep">•</span>
									<span class="meta-author"><?php the_author(); ?></span>
								</div>
								<h2 class="blog-card-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="blog-card-excerpt">
									<?php the_excerpt(); ?>
								</div>
								<a href="<?php the_permalink(); ?>" class="read-more-link">
									<?php esc_html_e( 'Read Article', 'pickwitty-tools-pro' ); ?>
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
								</a>
							</div>
						</article>

						<?php
						// Insert Ad Card periodically if enabled
						if ( $ad_frequency > 0 && $post_count % $ad_frequency === 0 && function_exists( 'pw_tools_render_ad_card' ) ) {
							pw_tools_render_ad_card();
						}
						endwhile;
					?>
				</div>

				<!-- Infinite Scroll Sentinel & Spinner -->
				<div id="pw-infinite-scroll-sentinel" data-page="1" data-max-pages="<?php echo esc_attr( $wp_query->max_num_pages ); ?>">
					<div class="spinner-loader" id="pw-scroll-spinner" style="display:none;">
						<div class="spinner"></div>
						<span><?php esc_html_e( 'Loading more posts...', 'pickwitty-tools-pro' ); ?></span>
					</div>
				</div>

			<?php else : ?>
				<div class="no-posts-found card-box text-center py-12">
					<h2><?php esc_html_e( 'No Posts Found', 'pickwitty-tools-pro' ); ?></h2>
					<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'pickwitty-tools-pro' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</main>

<?php
get_footer();
