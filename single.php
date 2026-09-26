<?php
/**
 * Single Post Template
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<main id="primary" class="site-main container py-8">
	<div class="main-layout-grid">
		<div class="content-area">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-container card-box' ); ?>>
					<header class="entry-header">
						<div class="post-categories">
							<?php the_category( ' ' ); ?>
						</div>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-meta">
							<span class="posted-on"><?php esc_html_e( 'Published on', 'pickwitty-tools-pro' ); ?> <?php echo esc_html( get_the_date() ); ?></span>
							<span class="byline"> <?php esc_html_e( 'by', 'pickwitty-tools-pro' ); ?> <?php the_author(); ?></span>
						</div>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-post-thumbnail">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					<?php endif; ?>

					<!-- Middle Ad Slot -->
					<?php if ( function_exists( 'pw_tools_render_ad' ) ) : ?>
						<?php pw_tools_render_ad( 'middle' ); ?>
					<?php endif; ?>

					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pickwitty-tools-pro' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>

					<footer class="entry-footer">
						<?php the_tags( '<div class="post-tags"><span class="tags-label">' . esc_html__( 'Tags:', 'pickwitty-tools-pro' ) . '</span> ', ', ', '</div>' ); ?>
					</footer>
				</article>

				<?php
				// Comments template if open
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile;
			?>
		</div>

		<?php get_sidebar(); ?>
	</div>
</main>

<?php
get_footer();
