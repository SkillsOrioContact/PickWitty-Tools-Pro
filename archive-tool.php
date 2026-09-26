<?php
/**
 * Tools Archive Page (`/tools/`) and Category Archive Template (`/tool-category/`)
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<div class="archive-header-banner">
	<div class="container text-center py-10">
		<h1 class="page-title text-3xl font-extrabold mb-2">
			<?php
			if ( is_tax( 'tool_category' ) ) {
				single_term_title();
			} else {
				esc_html_e( 'Software Utilities Directory', 'pickwitty-tools-pro' );
			}
			?>
		</h1>
		<p class="page-subtitle text-secondary">
			<?php
			if ( is_tax( 'tool_category' ) ) {
				the_archive_description();
			} else {
				esc_html_e( 'Browse our complete suite of free browser-based software tools.', 'pickwitty-tools-pro' );
			}
			?>
		</p>
	</div>
</div>

<main id="primary" class="site-main container py-8">
	<div class="tools-grid">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				$short_desc = get_post_meta( get_the_ID(), '_pw_tool_short_desc', true ) ?: get_the_excerpt();
				$tool_icon  = get_post_meta( get_the_ID(), '_pw_tool_icon', true ) ?: '⚡';
				?>
				<article class="tool-card">
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
			<?php endwhile; ?>
		<?php else : ?>
			<div class="card-box text-center py-12" style="grid-column: 1 / -1;">
				<p><?php esc_html_e( 'No tools found in this archive.', 'pickwitty-tools-pro' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
