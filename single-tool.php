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

			<div class="single-tool-title-wrapper">
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
