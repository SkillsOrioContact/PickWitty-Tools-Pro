/**
 * Main JavaScript File for PickWitty Tools Pro
 * Includes Dark Mode Switch, LocalStorage Tool Bookmarks, Star Rating,
 * Share/Fullscreen mode, Toast Notifications, and Back-To-Top.
 */
document.addEventListener('DOMContentLoaded', function() {
	// 1. Dark Mode Theme Switcher
	const themeToggleBtn = document.getElementById('theme-toggle-btn');
	const currentTheme   = localStorage.getItem('pw_theme') || 'light';

	if (currentTheme === 'dark') {
		document.documentElement.setAttribute('data-theme', 'dark');
	}

	if (themeToggleBtn) {
		themeToggleBtn.addEventListener('click', function() {
			const activeTheme = document.documentElement.getAttribute('data-theme');
			const newTheme    = activeTheme === 'dark' ? 'light' : 'dark';

			document.documentElement.setAttribute('data-theme', newTheme);
			localStorage.setItem('pw_theme', newTheme);
			showToast(newTheme === 'dark' ? '🌙 Dark Mode Activated' : '☀️ Light Mode Activated');
		});
	}

	// 2. LocalStorage Tool Bookmarks System
	const favBtn = document.querySelector('.favorite-toggle-btn');
	if (favBtn) {
		const toolId   = favBtn.getAttribute('data-tool-id');
		let favorites  = JSON.parse(localStorage.getItem('pw_fav_tools') || '[]');

		if (favorites.includes(toolId)) {
			favBtn.classList.add('active');
			favBtn.querySelector('.fav-label').textContent = 'Bookmarked';
		}

		favBtn.addEventListener('click', function() {
			favorites = JSON.parse(localStorage.getItem('pw_fav_tools') || '[]');
			if (favorites.includes(toolId)) {
				favorites = favorites.filter(id => id !== toolId);
				favBtn.classList.remove('active');
				favBtn.querySelector('.fav-label').textContent = 'Bookmark';
				showToast('Removed from bookmarks');
			} else {
				favorites.push(toolId);
				favBtn.classList.add('active');
				favBtn.querySelector('.fav-label').textContent = 'Bookmarked';
				showToast('Saved to your bookmarks! ❤️');
			}
			localStorage.setItem('pw_fav_tools', JSON.stringify(favorites));
		});
	}

	// 3. Share / Copy Tool URL
	const copyBtn = document.querySelector('.copy-link-btn');
	if (copyBtn) {
		copyBtn.addEventListener('click', function() {
			const url = copyBtn.getAttribute('data-url') || window.location.href;
			navigator.clipboard.writeText(url).then(() => {
				showToast('Tool link copied to clipboard! 📋');
			});
		});
	}

	// 4. Fullscreen Mode Toggle for Tool Playground
	const fullscreenBtn = document.getElementById('pw-fullscreen-btn');
	const playground    = document.querySelector('.single-tool-playground');

	if (fullscreenBtn && playground) {
		fullscreenBtn.addEventListener('click', function() {
			if (!document.fullscreenElement) {
				playground.requestFullscreen().catch(err => {
					showToast('Fullscreen not supported in this browser');
				});
			} else {
				document.exitFullscreen();
			}
		});
	}

	// 5. Interactive Star Rating System
	const starBtns = document.querySelectorAll('.star-rating-interactive .star-btn');
	const ratingContainer = document.querySelector('.star-rating-interactive');

	if (starBtns.length > 0 && ratingContainer) {
		const toolId = ratingContainer.getAttribute('data-tool-id');

		starBtns.forEach(star => {
			star.addEventListener('mouseover', function() {
				const val = parseInt(star.getAttribute('data-value'));
				starBtns.forEach((s, idx) => {
					if (idx < val) s.classList.add('hover');
					else s.classList.remove('hover');
				});
			});

			star.addEventListener('mouseleave', function() {
				starBtns.forEach(s => s.classList.remove('hover'));
			});

			star.addEventListener('click', function() {
				const val = parseInt(star.getAttribute('data-value'));
				submitRating(toolId, val);
			});
		});
	}

	function submitRating(toolId, ratingVal) {
		const formData = new FormData();
		formData.append('action', 'pw_submit_tool_rating');
		formData.append('tool_id', toolId);
		formData.append('rating', ratingVal);
		formData.append('nonce', pwToolsData.nonce);

		fetch(pwToolsData.ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			if (data.success) {
				showToast('★ ' + data.data.message);
				document.getElementById('rating-avg-display').textContent = data.data.avg;
				document.getElementById('rating-count-display').textContent = data.data.count;

				starBtns.forEach((s, idx) => {
					if (idx < ratingVal) s.classList.add('active');
					else s.classList.remove('active');
				});
			} else {
				showToast(data.data.message || 'Error recording rating');
			}
		});
	}

	// 6. Mobile Menu Drawer Toggle
	const menuToggle  = document.querySelector('.menu-toggle');
	const primaryMenu = document.getElementById('site-navigation');

	if (menuToggle && primaryMenu) {
		menuToggle.addEventListener('click', function() {
			const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
			menuToggle.setAttribute('aria-expanded', !expanded);
			primaryMenu.classList.toggle('nav-menu-active');
		});
	}

	// 7. Live Search / Filter in Tools Grid (Front Page)
	const searchInput = document.getElementById('pw-tools-search');
	const toolCards   = document.querySelectorAll('.tools-grid .tool-card:not(.ad-card-item)');

	if (searchInput && toolCards.length > 0) {
		searchInput.addEventListener('input', function(e) {
			const query = e.target.value.toLowerCase().trim();

			toolCards.forEach(function(card) {
				const title = card.getAttribute('data-title') || '';
				const desc  = card.getAttribute('data-desc') || '';

				if (title.includes(query) || desc.includes(query)) {
					card.style.display = 'flex';
				} else {
					card.style.display = 'none';
				}
			});
		});
	}

	// 8. Category Tab Filter
	const catTabs = document.querySelectorAll('.cat-tab');
	if (catTabs.length > 0 && toolCards.length > 0) {
		catTabs.forEach(function(tab) {
			tab.addEventListener('click', function() {
				catTabs.forEach(t => t.classList.remove('active'));
				tab.classList.add('active');

				const catSlug = tab.getAttribute('data-cat');

				toolCards.forEach(function(card) {
					const cardCats = card.getAttribute('data-categories') || '';
					if (catSlug === 'all' || cardCats.includes(catSlug)) {
						card.style.display = 'flex';
					} else {
						card.style.display = 'none';
					}
				});
			});
		});
	}

	// 9. Back to Top Button
	const backToTopBtn = document.createElement('button');
	backToTopBtn.id = 'pw-back-to-top';
	backToTopBtn.innerHTML = '↑';
	backToTopBtn.setAttribute('title', 'Back to Top');
	document.body.appendChild(backToTopBtn);

	window.addEventListener('scroll', function() {
		if (window.scrollY > 400) {
			backToTopBtn.classList.add('visible');
		} else {
			backToTopBtn.classList.remove('visible');
		}
	});

	backToTopBtn.addEventListener('click', function() {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});

	// Helper: Toast Messenger
	function showToast(msg) {
		let container = document.getElementById('pw-toast-container');
		if (!container) {
			container = document.createElement('div');
			container.id = 'pw-toast-container';
			document.body.appendChild(container);
		}

		const toast = document.createElement('div');
		toast.className = 'pw-toast';
		toast.textContent = msg;
		container.appendChild(toast);

		setTimeout(() => {
			toast.style.opacity = '0';
			setTimeout(() => toast.remove(), 300);
		}, 3000);
	}
});
