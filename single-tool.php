<?php
/**
 * Single Tool View Template
 * Formats Functional Application Playground + Bottom SEO Content + Meta Information + Ads
 *
 * @package PickWitty_Tools_Pro
 */

get_header();

while ( have_posts() ) :
	the_post();
	$short_desc = get_post_meta( get_the_ID(), '_pw_tool_short_desc', true );
	$tool_icon  = get_post_meta( get_the_ID(), '_pw_tool_icon', true ) ?: '⚡';
	$html_code  = get_post_meta( get_the_ID(), '_pw_tool_html', true );
	$categories = get_the_terms( get_the_ID(), 'tool_category' );
	?>

	<div class="single-tool-header">
		<div class="container">
			<nav class="pw-breadcrumb" aria-label="Breadcrumbs">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'pickwitty-tools-pro' ); ?></a>
				<span>/</span>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'tool' ) ); ?>"><?php esc_html_e( 'Tools', 'pickwitty-tools-pro' ); ?></a>
				<span>/</span>
				<span class="active"><?php the_title(); ?></span>
			</nav>

			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
				<div class="tool-badge-category">
					<?php echo esc_html( $categories[0]->name ); ?>
				</div>
			<?php endif; ?>

			<div class="single-tool-title-wrapper flex-between flex-wrap gap-4">
				<div class="flex-align-center gap-4">
					<div class="single-tool-icon-box">
						<span><?php echo esc_html( $tool_icon ); ?></span>
					</div>
					<div>
						<h1 class="single-tool-main-title"><?php the_title(); ?></h1>
						<?php if ( $short_desc ) : ?>
							<p class="single-tool-short-desc"><?php echo esc_html( $short_desc ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<!-- Tool Action Controls Bar -->
				<div class="tool-action-toolbar">
					<button class="tool-action-btn favorite-toggle-btn" data-tool-id="<?php the_ID(); ?>" title="<?php esc_attr_e( 'Bookmark / Favorite Tool', 'pickwitty-tools-pro' ); ?>">
						<svg class="heart-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
						<span class="fav-label"><?php esc_html_e( 'Bookmark', 'pickwitty-tools-pro' ); ?></span>
					</button>

					<button class="tool-action-btn copy-link-btn" data-url="<?php echo esc_url( get_permalink() ); ?>" title="<?php esc_attr_e( 'Copy Tool Link', 'pickwitty-tools-pro' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
						<span><?php esc_html_e( 'Share', 'pickwitty-tools-pro' ); ?></span>
					</button>

					<button class="tool-action-btn fullscreen-toggle-btn" id="pw-fullscreen-btn" title="<?php esc_attr_e( 'Toggle Fullscreen App', 'pickwitty-tools-pro' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
						<span><?php esc_html_e( 'Fullscreen Mode', 'pickwitty-tools-pro' ); ?></span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<main id="primary" class="site-main container py-6">
		<!-- Top / Header Tool Ad Slot -->
		<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
			<?php pw_tools_render_ad( 'header' ); ?>
		<?php endif; ?>

		<!-- Functional SaaS Tool Canvas & Playground -->
		<div class="single-tool-playground">
			<?php
			if ( ! empty( $html_code ) ) {
				echo $html_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				?>
				<div class="card-box text-center py-12">
					<p class="text-secondary"><?php esc_html_e( 'No functional code provided for this tool yet. Edit this tool in WordPress admin to add HTML, CSS, and JS code.', 'pickwitty-tools-pro' ); ?></p>
				</div>
				<?php
			}
			?>
		</div>

		<!-- Middle Ad Slot -->
		<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
			<?php pw_tools_render_ad( 'middle' ); ?>
		<?php endif; ?>

		<!-- Tool Star Rating & User Feedback Section -->
		<?php
		$rating_sum   = (int) get_post_meta( get_the_ID(), '_pw_tool_rating_sum', true );
		$rating_count = (int) get_post_meta( get_the_ID(), '_pw_tool_rating_count', true );
		$avg_rating   = $rating_count > 0 ? round( $rating_sum / $rating_count, 1 ) : 5.0;
		if ( $rating_count === 0 ) {
			$rating_count = 1; // Default initial social proof
		}
		?>
		<div class="tool-rating-widget-box card-box my-6 text-center">
			<h3 class="text-lg font-bold mb-2"><?php esc_html_e( 'How helpful was this tool?', 'pickwitty-tools-pro' ); ?></h3>
			<div class="star-rating-interactive flex-center gap-2 mb-2" data-tool-id="<?php the_ID(); ?>">
				<span class="star-btn" data-value="1">★</span>
				<span class="star-btn" data-value="2">★</span>
				<span class="star-btn" data-value="3">★</span>
				<span class="star-btn" data-value="4">★</span>
				<span class="star-btn" data-value="5">★</span>
			</div>
			<p class="text-sm text-secondary">
				<span id="rating-avg-display" class="font-bold text-primary"><?php echo esc_html( $avg_rating ); ?></span> / 5
				(<span id="rating-count-display"><?php echo esc_html( $rating_count ); ?></span> <?php esc_html_e( 'votes', 'pickwitty-tools-pro' ); ?>)
			</p>
		</div>

		<!-- Bottom Rich Text Description for SEO & User Information -->
		<?php if ( get_the_content() ) : ?>
			<article id="tool-seo-info" class="tool-long-description-section card-box">
				<div class="tool-rich-text">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>
	</main>

	<?php
endwhile;

get_footer();
