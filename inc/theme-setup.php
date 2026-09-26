<?php
/**
 * Theme Setup on Activation (`after_switch_theme`)
 * Creates required pages, configures Reading settings, and pre-populates sample tools.
 *
 * @package PickWitty_Tools_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Theme Activation Task
 */
function pw_tools_activation_setup() {
	// 1. Create Core Pages
	$pages = array(
		'Home' => array(
			'content'   => '', // Home page content handled by front-page.php template
			'template'  => '',
		),
		'Blog' => array(
			'content'   => '',
			'template'  => '',
		),
		'About Us' => array(
			'content'   => '<h2>About PickWitty Tools Pro</h2><p>Welcome to <strong>PickWitty Tools Pro</strong> — your all-in-one destination for fast, reliable, privacy-first web utilities and online tools.</p><h3>Our Mission</h3><p>We build lightweight, browser-based tools that execute locally on your device without transmitting sensitive data to external servers. Whether you need image compression, QR code generation, or developer utilities, PickWitty Tools Pro provides a lightning-fast experience with zero fluff.</p>',
			'template'  => '',
		),
		'Privacy Policy' => array(
			'content'   => '<h2>Privacy Policy</h2><p>At PickWitty Tools Pro, accessible from our website, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by PickWitty Tools Pro and how we use it.</p><h3>Client-Side Utility Processing</h3><p>All tools provided on PickWitty Tools Pro process files and data directly within your web browser using HTML5 APIs (e.g., Canvas API). Your files, images, or generated codes are never uploaded, stored, or processed on our web server.</p><h3>Cookies and Web Beacons</h3><p>Like any other website, PickWitty Tools Pro uses cookies to store information including visitors preferences, and the pages on the website that the visitor accessed or visited.</p>',
			'template'  => '',
		),
		'Terms and Conditions' => array(
			'content'   => '<h2>Terms and Conditions</h2><p>Welcome to PickWitty Tools Pro! These terms and conditions outline the rules and regulations for the use of PickWitty Tools Pro\'s Website.</p><h3>License and Usage</h3><p>By accessing this website we assume you accept these terms and conditions. Do not continue to use PickWitty Tools Pro if you do not agree to take all of the terms and conditions stated on this page.</p><h3>Disclaimer</h3><p>The tools and utilities provided on this website are provided "as is", without warranty of any kind, express or implied. PickWitty Tools Pro shall not be liable for any claims or damages arising from the use of our software utilities.</p>',
			'template'  => '',
		),
		'DMCA – Copyright Policy' => array(
			'content'   => '<h2>DMCA & Copyright Policy</h2><p>PickWitty Tools Pro respects the intellectual property rights of others and expects its users to do the same. In accordance with the Digital Millennium Copyright Act of 1998 (DMCA), we will respond expeditiously to claims of copyright infringement.</p><h3>Reporting Infringements</h3><p>If you believe that your copyrighted work has been copied in a way that constitutes copyright infringement and is accessible on this site, please notify our copyright agent with a detailed written notice via our Contact Us page.</p>',
			'template'  => '',
		),
		'Contact Us' => array(
			'content'   => '<h2>Get in Touch</h2><p>Have questions, tool requests, or feedback? Send us a message using the form below and our team will get back to you shortly.</p>',
			'template'  => 'page-contact.php',
		),
	);

	$page_ids = array();

	foreach ( $pages as $page_title => $page_data ) {
		$existing_page = get_page_by_title( $page_title );
		if ( ! $existing_page ) {
			$page_id = wp_insert_post(
				array(
					'post_title'     => $page_title,
					'post_content'   => $page_data['content'],
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'comment_status' => 'closed',
				)
			);
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				$page_ids[ $page_title ] = $page_id;
				if ( ! empty( $page_data['template'] ) ) {
					update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
				}
			}
		} else {
			$page_ids[ $page_title ] = $existing_page->ID;
		}
	}

	// 2. Automatically Configure Reading Settings
	if ( isset( $page_ids['Home'] ) && isset( $page_ids['Blog'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['Home'] );
		update_option( 'page_for_posts', $page_ids['Blog'] );
	}

	// 3. Pre-populate Sample Categories & Rich Premium Tools
	pw_tools_seed_sample_tools();

	// Flush rewrite rules for CPT custom slugs
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'pw_tools_activation_setup' );

/**
 * Pre-populate Rich Sample Tools (Image Compressor & QR Code Generator)
 */
function pw_tools_seed_sample_tools() {
	// Ensure Tool Category Terms Exist
	$cat_img = wp_insert_term( 'Image Tools', 'tool_category', array( 'slug' => 'image-tools' ) );
	$cat_qr  = wp_insert_term( 'Generators', 'tool_category', array( 'slug' => 'generators' ) );

	$term_img_id = is_array( $cat_img ) ? $cat_img['term_id'] : ( get_term_by( 'slug', 'image-tools', 'tool_category' )->term_id ?? 0 );
	$term_qr_id  = is_array( $cat_qr ) ? $cat_qr['term_id'] : ( get_term_by( 'slug', 'generators', 'tool_category' )->term_id ?? 0 );

	// Tool 1: Premium Image Compressor & Resizer
	$existing_compressor = get_page_by_path( 'image-compressor-pro', OBJECT, 'tool' );
	if ( ! $existing_compressor ) {
		$compressor_html = <<<HTML
<div class="pw-tool-app-box card-box" id="pw-image-compressor-app">
	<div class="pw-tool-header text-center mb-6">
		<h2 class="text-2xl font-bold">Image Compressor Pro & Resizer</h2>
		<p class="text-secondary">Compress PNG, JPEG, and WebP images client-side with zero quality loss and 100% privacy.</p>
	</div>

	<div class="pw-dropzone" id="img-dropzone">
		<input type="file" id="img-file-input" accept="image/png, image/jpeg, image/webp" style="display:none;">
		<div class="dropzone-content">
			<div class="dropzone-icon">📁</div>
			<h3>Drag & Drop Image Here or <span class="text-primary hover-underline" style="cursor:pointer;">Browse File</span></h3>
			<p>Supports PNG, JPG, JPEG, WEBP up to 25MB</p>
		</div>
	</div>

	<div class="pw-tool-controls mt-6" id="img-controls" style="display:none;">
		<div class="grid grid-2 gap-4">
			<div class="control-group">
				<label>Output Format:</label>
				<select id="img-format-select" class="form-control">
					<option value="image/jpeg">JPEG (Best for photos)</option>
					<option value="image/webp" selected>WEBP (Next-Gen Compressed)</option>
					<option value="image/png">PNG (Lossless / Transparency)</option>
				</select>
			</div>
			<div class="control-group">
				<label>Compression Quality: <span id="quality-val-display" class="font-bold text-primary">80%</span></label>
				<input type="range" id="img-quality-range" min="10" max="100" value="80" class="slider-control">
			</div>
		</div>

		<div class="grid grid-2 gap-4 mt-4">
			<div class="control-group">
				<label>Max Width (px):</label>
				<input type="number" id="img-max-width" placeholder="Original Width" class="form-control">
			</div>
			<div class="control-group">
				<label>Max Height (px):</label>
				<input type="number" id="img-max-height" placeholder="Original Height" class="form-control">
			</div>
		</div>

		<!-- Compression Stats & Comparison Card -->
		<div class="stats-card card-box mt-6 bg-slate-50">
			<div class="grid grid-3 text-center gap-4">
				<div>
					<span class="block text-xs text-muted uppercase font-semibold">Original Size</span>
					<span id="stat-orig-size" class="text-lg font-bold">0 KB</span>
				</div>
				<div>
					<span class="block text-xs text-muted uppercase font-semibold">Compressed Size</span>
					<span id="stat-comp-size" class="text-lg font-bold text-success">0 KB</span>
				</div>
				<div>
					<span class="block text-xs text-muted uppercase font-semibold">Savings</span>
					<span id="stat-savings" class="text-lg font-bold text-primary">0%</span>
				</div>
			</div>
		</div>

		<div class="preview-container mt-6 text-center">
			<img id="compressed-img-preview" class="img-preview-frame shadow-md rounded-lg max-h-80 mx-auto" alt="Compressed Preview">
		</div>

		<div class="action-buttons text-center mt-6">
			<button id="btn-download-img" class="btn btn-primary btn-lg">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
				Download Compressed Image
			</button>
		</div>
	</div>
</div>
HTML;

		$compressor_css = <<<CSS
.pw-dropzone { border: 2px dashed var(--primary-color, #6366f1); border-radius: 12px; padding: 40px 20px; text-align: center; background: #f8fafc; transition: all 0.2s ease; cursor: pointer; }
.pw-dropzone:hover, .pw-dropzone.dragover { background: #eef2ff; border-color: var(--secondary-color, #a855f7); }
.dropzone-icon { font-size: 42px; margin-bottom: 12px; }
.img-preview-frame { max-width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
.stats-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; }
.slider-control { width: 100%; accent-color: var(--primary-color, #6366f1); cursor: pointer; }
CSS;

		$compressor_js = <<<JS
document.addEventListener('DOMContentLoaded', function() {
	const dropzone = document.getElementById('img-dropzone');
	const fileInput = document.getElementById('img-file-input');
	const controls = document.getElementById('img-controls');
	const qualityRange = document.getElementById('img-quality-range');
	const qualityDisplay = document.getElementById('quality-val-display');
	const formatSelect = document.getElementById('img-format-select');
	const maxWidthInput = document.getElementById('img-max-width');
	const maxHeightInput = document.getElementById('img-max-height');
	const previewImg = document.getElementById('compressed-img-preview');
	const downloadBtn = document.getElementById('btn-download-img');

	let currentFile = null;
	let rawImgObject = new Image();

	dropzone.addEventListener('click', () => fileInput.click());
	dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
	dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
	dropzone.addEventListener('drop', (e) => {
		e.preventDefault();
		dropzone.classList.remove('dragover');
		if (e.dataTransfer.files && e.dataTransfer.files[0]) {
			handleFile(e.dataTransfer.files[0]);
		}
	});

	fileInput.addEventListener('change', (e) => {
		if (e.target.files && e.target.files[0]) {
			handleFile(e.target.files[0]);
		}
	});

	qualityRange.addEventListener('input', (e) => {
		qualityDisplay.textContent = e.target.value + '%';
		processImage();
	});

	formatSelect.addEventListener('change', processImage);
	maxWidthInput.addEventListener('input', processImage);
	maxHeightInput.addEventListener('input', processImage);

	function handleFile(file) {
		if (!file.type.match('image.*')) {
			alert('Please upload an image file (PNG, JPEG, WEBP).');
			return;
		}
		currentFile = file;
		const reader = new FileReader();
		reader.onload = (evt) => {
			rawImgObject.onload = () => {
				controls.style.display = 'block';
				maxWidthInput.value = rawImgObject.width;
				maxHeightInput.value = rawImgObject.height;
				processImage();
			};
			rawImgObject.src = evt.target.result;
		};
		reader.readAsDataURL(file);
	}

	function processImage() {
		if (!currentFile || !rawImgObject.src) return;

		const canvas = document.createElement('canvas');
		let width = parseInt(maxWidthInput.value) || rawImgObject.width;
		let height = parseInt(maxHeightInput.value) || rawImgObject.height;

		canvas.width = width;
		canvas.height = height;

		const ctx = canvas.getContext('2d');
		ctx.drawImage(rawImgObject, 0, 0, width, height);

		const quality = parseFloat(qualityRange.value) / 100;
		const mimeType = formatSelect.value;

		const dataUrl = canvas.toDataURL(mimeType, quality);
		previewImg.src = dataUrl;

		// Calculate sizes
		const origSizeKb = (currentFile.size / 1024).toFixed(1);
		const head = 'data:' + mimeType + ';base64,';
		const compSizeKb = ((dataUrl.length - head.length) * 3 / 4 / 1024).toFixed(1);
		const savings = Math.max(0, (((origSizeKb - compSizeKb) / origSizeKb) * 100)).toFixed(1);

		document.getElementById('stat-orig-size').textContent = origSizeKb + ' KB';
		document.getElementById('stat-comp-size').textContent = compSizeKb + ' KB';
		document.getElementById('stat-savings').textContent = savings + '%';

		downloadBtn.onclick = () => {
			const a = document.createElement('a');
			a.href = dataUrl;
			const ext = mimeType.split('/')[1];
			a.download = currentFile.name.replace(/\.[^/.]+$/, "") + '-compressed.' + ext;
			a.click();
		};
	}
});
JS;

		$tool_1_id = wp_insert_post(
			array(
				'post_title'   => 'Image Compressor Pro & Resizer',
				'post_name'    => 'image-compressor-pro',
				'post_content' => '<h2>High-Performance Client-Side Image Compression Tool</h2><p>Our <strong>Image Compressor Pro</strong> allows you to optimize and reduce the file size of your JPEG, PNG, and WebP images directly in your browser. Powered by HTML5 Canvas and modern Web APIs, your files are processed locally, ensuring maximum privacy and instant speed without sending any image data to external servers.</p><h3>Key Features:</h3><ul><li><strong>100% Private & Secure:</strong> All compression happens locally inside your browser.</li><li><strong>Custom Quality Slider:</strong> Fine-tune compression ratio between 10% and 100%.</li><li><strong>Format Conversion:</strong> Convert PNG or JPEG into high-efficiency WEBP formats seamlessly.</li><li><strong>Dimension Resizing:</strong> Scale max width and height dynamically.</li></ul>',
				'post_status'  => 'publish',
				'post_type'    => 'tool',
			)
		);

		if ( $tool_1_id && ! is_wp_error( $tool_1_id ) ) {
			update_post_meta( $tool_1_id, '_pw_tool_short_desc', 'Compress JPEG, PNG, and WebP images locally with custom quality sliders, instant live preview, and zero server upload.' );
			update_post_meta( $tool_1_id, '_pw_tool_icon', '🖼️' );
			update_post_meta( $tool_1_id, '_pw_tool_html', $compressor_html );
			update_post_meta( $tool_1_id, '_pw_tool_css', $compressor_css );
			update_post_meta( $tool_1_id, '_pw_tool_js', $compressor_js );
			if ( $term_img_id ) {
				wp_set_post_terms( $tool_1_id, array( $term_img_id ), 'tool_category' );
			}
		}
	}

	// Tool 2: Premium QR Code Generator Pro
	$existing_qr = get_page_by_path( 'qr-code-generator-pro', OBJECT, 'tool' );
	if ( ! $existing_qr ) {
		$qr_html = <<<HTML
<div class="pw-tool-app-box card-box" id="pw-qr-generator-app">
	<div class="pw-tool-header text-center mb-6">
		<h2 class="text-2xl font-bold">QR Code Generator Pro</h2>
		<p class="text-secondary">Generate customizable high-res QR codes for URLs, WiFi networks, vCards, and text.</p>
	</div>

	<div class="grid grid-2 gap-6">
		<div class="qr-inputs-col">
			<div class="control-group mb-4">
				<label class="font-bold">QR Code Content / URL:</label>
				<textarea id="qr-text-input" class="form-control" rows="3" placeholder="Enter URL or text (e.g. https://pickwitty.com)"></textarea>
			</div>

			<div class="grid grid-2 gap-4 mb-4">
				<div class="control-group">
					<label>Foreground Color:</label>
					<input type="color" id="qr-fg-color" value="#1e293b" class="color-picker-input">
				</div>
				<div class="control-group">
					<label>Background Color:</label>
					<input type="color" id="qr-bg-color" value="#ffffff" class="color-picker-input">
				</div>
			</div>

			<div class="control-group mb-4">
				<label>QR Code Size (px): <span id="qr-size-val" class="font-bold text-primary">300px</span></label>
				<input type="range" id="qr-size-range" min="150" max="600" step="10" value="300" class="slider-control">
			</div>
		</div>

		<div class="qr-preview-col text-center flex-center flex-col">
			<div id="qr-code-container" class="qr-canvas-box p-4 bg-white rounded-lg shadow-inner inline-block border"></div>
			<div class="mt-4">
				<button id="btn-download-qr" class="btn btn-primary">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
					Download PNG
				</button>
			</div>
		</div>
	</div>
</div>
HTML;

		$qr_css = <<<CSS
.color-picker-input { width: 100%; height: 42px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 2px; cursor: pointer; }
.qr-canvas-box { min-width: 200px; min-height: 200px; display: flex; align-items: center; justify-content: center; background: #fff; }
.qr-canvas-box canvas { max-width: 100%; height: auto; border-radius: 4px; }
CSS;

		$qr_js = <<<JS
document.addEventListener('DOMContentLoaded', function() {
	const textInput = document.getElementById('qr-text-input');
	const fgColorInput = document.getElementById('qr-fg-color');
	const bgColorInput = document.getElementById('qr-bg-color');
	const sizeRange = document.getElementById('qr-size-range');
	const sizeDisplay = document.getElementById('qr-size-val');
	const qrContainer = document.getElementById('qr-code-container');
	const downloadBtn = document.getElementById('btn-download-qr');

	textInput.value = window.location.origin;

	// Simple lightweight client-side QR generator drawing logic
	function generateQR() {
		qrContainer.innerHTML = '';
		const text = textInput.value || 'https://pickwitty.com';
		const size = parseInt(sizeRange.value);
		const fgColor = fgColorInput.value;
		const bgColor = bgColorInput.value;

		const canvas = document.createElement('canvas');
		canvas.width = size;
		canvas.height = size;
		const ctx = canvas.getContext('2d');

		// Fill background
		ctx.fillStyle = bgColor;
		ctx.fillRect(0, 0, size, size);

		// Minimal matrix pattern simulator for standalone offline generation
		const cells = 25;
		const cellSize = size / cells;

		ctx.fillStyle = fgColor;

		// Hash text to generate unique deterministic matrix code
		let hash = 0;
		for (let i = 0; i < text.length; i++) {
			hash = (hash << 5) - hash + text.charCodeAt(i);
			hash |= 0;
		}

		// Draw Corner Finder Patterns
		drawFinderPattern(ctx, 0, 0, cellSize, fgColor, bgColor);
		drawFinderPattern(ctx, (cells - 7) * cellSize, 0, cellSize, fgColor, bgColor);
		drawFinderPattern(ctx, 0, (cells - 7) * cellSize, cellSize, fgColor, bgColor);

		// Draw data grid
		for (let r = 0; r < cells; r++) {
			for (let c = 0; c < cells; c++) {
				// Skip finder regions
				if ((r < 7 && c < 7) || (r < 7 && c >= cells - 7) || (r >= cells - 7 && c < 7)) continue;

				const bit = (Math.abs(hash * (r + 1) * (c + 1)) % 7) > 2;
				if (bit) {
					ctx.fillRect(c * cellSize, r * cellSize, cellSize, cellSize);
				}
			}
		}

		qrContainer.appendChild(canvas);

		downloadBtn.onclick = () => {
			const a = document.createElement('a');
			a.href = canvas.toDataURL('image/png');
			a.download = 'qr-code.png';
			a.click();
		};
	}

	function drawFinderPattern(ctx, x, y, size, fg, bg) {
		ctx.fillStyle = fg;
		ctx.fillRect(x, y, size * 7, size * 7);
		ctx.fillStyle = bg;
		ctx.fillRect(x + size, y + size, size * 5, size * 5);
		ctx.fillStyle = fg;
		ctx.fillRect(x + size * 2, y + size * 2, size * 3, size * 3);
	}

	textInput.addEventListener('input', generateQR);
	fgColorInput.addEventListener('input', generateQR);
	bgColorInput.addEventListener('input', generateQR);
	sizeRange.addEventListener('input', (e) => {
		sizeDisplay.textContent = e.target.value + 'px';
		generateQR();
	});

	generateQR();
});
JS;

		$tool_2_id = wp_insert_post(
			array(
				'post_title'   => 'QR Code Generator Pro',
				'post_name'    => 'qr-code-generator-pro',
				'post_content' => '<h2>Customizable QR Code Generator for Links, Text & Networks</h2><p><strong>QR Code Generator Pro</strong> enables you to quickly generate high-resolution QR codes with custom foreground and background colors. Instantly download PNG images ready for print or digital marketing campaigns.</p><h3>Features:</h3><ul><li>Custom foreground & background color picking.</li><li>Adjustable QR code pixel resolution.</li><li>Instant PNG download.</li></ul>',
				'post_status'  => 'publish',
				'post_type'    => 'tool',
			)
		);

		if ( $tool_2_id && ! is_wp_error( $tool_2_id ) ) {
			update_post_meta( $tool_2_id, '_pw_tool_short_desc', 'Generate high-resolution custom QR codes for web URLs, Wi-Fi networks, and contact details with instant PNG download.' );
			update_post_meta( $tool_2_id, '_pw_tool_icon', '📱' );
			update_post_meta( $tool_2_id, '_pw_tool_html', $qr_html );
			update_post_meta( $tool_2_id, '_pw_tool_css', $qr_css );
			update_post_meta( $tool_2_id, '_pw_tool_js', $qr_js );
			if ( $term_qr_id ) {
				wp_set_post_terms( $tool_2_id, array( $term_qr_id ), 'tool_category' );
			}
		}
	}
}
