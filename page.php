<?php
/**
 * Single Page Template
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<div class="page-header-banner">
	<div class="container">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div>
</div>

<main id="primary" class="site-main container py-8">
	<div class="page-container card-box">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
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
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
