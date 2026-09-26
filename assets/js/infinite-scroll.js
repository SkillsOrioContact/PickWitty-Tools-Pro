/**
 * AJAX Infinite Scroll JavaScript Implementation
 */
document.addEventListener('DOMContentLoaded', function() {
	const sentinel = document.getElementById('pw-infinite-scroll-sentinel');
	const blogGrid = document.getElementById('pw-blog-grid');
	const spinner  = document.getElementById('pw-scroll-spinner');

	if (!sentinel || !blogGrid) return;

	let currentPage = parseInt(sentinel.getAttribute('data-page')) || 1;
	let maxPages    = parseInt(sentinel.getAttribute('data-max-pages')) || 1;
	let isLoading   = false;

	const urlParams = new URLSearchParams(window.location.search);
	const yearFilter  = urlParams.get('pw_archive_year') || '';
	const monthFilter = urlParams.get('pw_archive_month') || '';

	const observer = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.isIntersecting && !isLoading && currentPage < maxPages) {
				loadMorePosts();
			}
		});
	}, { rootMargin: '200px' });

	observer.observe(sentinel);

	function loadMorePosts() {
		isLoading = true;
		if (spinner) spinner.style.display = 'flex';

		const nextPage = currentPage + 1;
		const formData = new FormData();
		formData.append('action', 'pw_load_more_posts');
		formData.append('page', nextPage);
		formData.append('year', yearFilter);
		formData.append('month', monthFilter);
		formData.append('nonce', pwToolsData.nonce);

		fetch(pwToolsData.ajaxUrl, {
			method: 'POST',
			body: formData
		})
		.then(res => res.json())
		.then(data => {
			if (data.success && data.data.html) {
				blogGrid.insertAdjacentHTML('beforeend', data.data.html);
				currentPage = nextPage;
				sentinel.setAttribute('data-page', currentPage);
				if (currentPage >= maxPages) {
					observer.unobserve(sentinel);
				}
			} else {
				observer.unobserve(sentinel);
			}
		})
		.catch(err => console.error('Error loading posts:', err))
		.finally(() => {
			isLoading = false;
			if (spinner) spinner.style.display = 'none';
		});
	}
});
