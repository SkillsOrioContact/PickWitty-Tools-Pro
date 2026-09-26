<?php
/**
 * AJAX Handlers for Infinite Scroll & Contact Form Submissions
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AJAX Handler for Infinite Scroll Blog Posts
 */
function pw_tools_load_more_posts() {
	check_ajax_referer( 'pw_tools_nonce', 'nonce' );

	$page  = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$year  = isset( $_POST['year'] ) ? sanitize_text_field( $_POST['year'] ) : '';
	$month = isset( $_POST['month'] ) ? sanitize_text_field( $_POST['month'] ) : '';

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => get_option( 'posts_per_page', 6 ),
		'paged'          => $page,
		'post_status'    => 'publish',
	);

	if ( ! empty( $year ) ) {
		$args['year'] = $year;
	}
	if ( ! empty( $month ) ) {
		$args['monthnum'] = $month;
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		ob_start();
		$count = 0;
		$ad_frequency = (int) get_theme_mod( 'pw_adsense_card_freq', 3 );

		while ( $query->have_posts() ) {
			$query->the_post();
			$count++;
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
			if ( $ad_frequency > 0 && $count % $ad_frequency === 0 && function_exists( 'pw_tools_render_ad_card' ) ) {
				pw_tools_render_ad_card();
			}
		}
		wp_reset_postdata();

		$html = ob_get_clean();

		wp_send_json_success(
			array(
				'html'      => $html,
				'max_pages' => $query->max_num_pages,
			)
		);
	} else {
		wp_send_json_error( array( 'message' => __( 'No more posts found', 'pickwitty-tools-pro' ) ) );
	}
}
add_action( 'wp_ajax_pw_load_more_posts', 'pw_tools_load_more_posts' );
add_action( 'wp_ajax_nopriv_pw_load_more_posts', 'pw_tools_load_more_posts' );

/**
 * AJAX Handler for Contact Form
 */
function pw_tools_submit_contact_form() {
	check_ajax_referer( 'pw_tools_nonce', 'nonce' );

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'pickwitty-tools-pro' ) ) );
	}

	$admin_email = get_option( 'admin_email' );
	$headers     = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . $name . ' <' . $email . '>',
	);

	$mail_body = "<strong>Name:</strong> " . esc_html( $name ) . "<br>" .
				 "<strong>Email:</strong> " . esc_html( $email ) . "<br><br>" .
				 "<strong>Message:</strong><br>" . nl2br( esc_html( $message ) );

	wp_mail( $admin_email, 'Contact Form: ' . $subject, $mail_body, $headers );

	wp_send_json_success( array( 'message' => __( 'Thank you! Your message has been sent successfully.', 'pickwitty-tools-pro' ) ) );
}
add_action( 'wp_ajax_pw_submit_contact', 'pw_tools_submit_contact_form' );
add_action( 'wp_ajax_nopriv_pw_submit_contact', 'pw_tools_submit_contact_form' );
