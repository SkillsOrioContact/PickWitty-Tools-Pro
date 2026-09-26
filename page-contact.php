<?php
/**
 * Template Name: Contact Us Page Template
 *
 * @package PickWitty_Tools_Pro
 */

get_header();
?>

<div class="page-header-banner">
	<div class="container text-center py-10">
		<h1 class="page-title text-3xl font-extrabold"><?php the_title(); ?></h1>
		<p class="page-subtitle text-secondary"><?php esc_html_e( 'Have questions, feedback, or tool requests? Get in touch with us below.', 'pickwitty-tools-pro' ); ?></p>
	</div>
</div>

<main id="primary" class="site-main container py-8">
	<div class="grid grid-2 gap-8 max-w-4xl mx-auto">
		<div class="card-box">
			<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Send Us a Message', 'pickwitty-tools-pro' ); ?></h2>

			<div id="contact-form-response" class="form-response-alert" style="display:none; margin-bottom: 16px; padding: 12px; border-radius: 6px;"></div>

			<form id="pw-contact-form" class="contact-form">
				<div class="control-group mb-4">
					<label class="font-semibold block mb-1"><?php esc_html_e( 'Your Name *', 'pickwitty-tools-pro' ); ?></label>
					<input type="text" name="name" required class="form-control w-full" placeholder="<?php esc_attr_e( 'John Doe', 'pickwitty-tools-pro' ); ?>">
				</div>

				<div class="control-group mb-4">
					<label class="font-semibold block mb-1"><?php esc_html_e( 'Your Email *', 'pickwitty-tools-pro' ); ?></label>
					<input type="email" name="email" required class="form-control w-full" placeholder="<?php esc_attr_e( 'john@example.com', 'pickwitty-tools-pro' ); ?>">
				</div>

				<div class="control-group mb-4">
					<label class="font-semibold block mb-1"><?php esc_html_e( 'Subject', 'pickwitty-tools-pro' ); ?></label>
					<input type="text" name="subject" class="form-control w-full" placeholder="<?php esc_attr_e( 'Tool Feedback / Bug Report', 'pickwitty-tools-pro' ); ?>">
				</div>

				<div class="control-group mb-4">
					<label class="font-semibold block mb-1"><?php esc_html_e( 'Message *', 'pickwitty-tools-pro' ); ?></label>
					<textarea name="message" rows="5" required class="form-control w-full" placeholder="<?php esc_attr_e( 'Type your message here...', 'pickwitty-tools-pro' ); ?>"></textarea>
				</div>

				<button type="submit" id="contact-submit-btn" class="btn btn-primary btn-lg w-full">
					<?php esc_html_e( 'Send Message', 'pickwitty-tools-pro' ); ?>
				</button>
			</form>
		</div>

		<div class="card-box flex-center flex-col text-center">
			<div class="contact-info-icon text-4xl mb-4">📫</div>
			<h3 class="text-xl font-bold mb-2"><?php esc_html_e( 'Contact Information', 'pickwitty-tools-pro' ); ?></h3>
			<p class="text-secondary mb-6"><?php esc_html_e( 'We respond to all inquiries within 24-48 business hours.', 'pickwitty-tools-pro' ); ?></p>

			<div class="contact-detail-item mb-3">
				<strong><?php esc_html_e( 'Email:', 'pickwitty-tools-pro' ); ?></strong>
				<span><?php echo esc_html( get_option( 'admin_email' ) ); ?></span>
			</div>

			<div class="contact-detail-item">
				<strong><?php esc_html_e( 'Author / Publisher:', 'pickwitty-tools-pro' ); ?></strong>
				<span>PickWitty</span>
			</div>
		</div>
	</div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const form = document.getElementById('pw-contact-form');
	const responseDiv = document.getElementById('contact-form-response');
	const submitBtn = document.getElementById('contact-submit-btn');

	if (form) {
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			submitBtn.disabled = true;
			submitBtn.textContent = 'Sending...';

			const formData = new FormData(form);
			formData.append('action', 'pw_submit_contact');
			formData.append('nonce', pwToolsData.nonce);

			fetch(pwToolsData.ajaxUrl, {
				method: 'POST',
				body: formData
			})
			.then(res => res.json())
			.then(data => {
				responseDiv.style.display = 'block';
				if (data.success) {
					responseDiv.style.background = '#dcfce7';
					responseDiv.style.color = '#166534';
					responseDiv.textContent = data.data.message;
					form.reset();
				} else {
					responseDiv.style.background = '#fee2e2';
					responseDiv.style.color = '#991b1b';
					responseDiv.textContent = data.data.message || 'An error occurred.';
				}
			})
			.catch(err => {
				responseDiv.style.display = 'block';
				responseDiv.style.background = '#fee2e2';
				responseDiv.style.color = '#991b1b';
				responseDiv.textContent = 'Server error. Please try again.';
			})
			.finally(() => {
				submitBtn.disabled = false;
				submitBtn.textContent = 'Send Message';
			});
		});
	}
});
</script>

<?php
get_footer();
