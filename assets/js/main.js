/**
 * Main JavaScript File for Theme Navigation & UI Interactions
 */
document.addEventListener('DOMContentLoaded', function() {
	// Mobile Menu Toggle
	const menuToggle = document.querySelector('.menu-toggle');
	const primaryMenu = document.getElementById('site-navigation');

	if (menuToggle && primaryMenu) {
		menuToggle.addEventListener('click', function() {
			const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
			menuToggle.setAttribute('aria-expanded', !expanded);
			primaryMenu.classList.toggle('nav-menu-active');
		});
	}

	// Live Search / Filter in Tools Grid (Front Page)
	const searchInput = document.getElementById('pw-tools-search');
	const toolCards = document.querySelectorAll('.tools-grid .tool-card:not(.ad-card-item)');

	if (searchInput && toolCards.length > 0) {
		searchInput.addEventListener('input', function(e) {
			const query = e.target.value.toLowerCase().trim();

			toolCards.forEach(function(card) {
				const title = card.getAttribute('data-title') || '';
				const desc = card.getAttribute('data-desc') || '';

				if (title.includes(query) || desc.includes(query)) {
					card.style.display = 'flex';
				} else {
					card.style.display = 'none';
				}
			});
		});
	}

	// Category Tab Filter
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
});
