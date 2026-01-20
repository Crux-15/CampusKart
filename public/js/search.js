document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const searchInput = document.getElementById('main-search');
    const searchForm = document.querySelector('.search-form');
    const filterForm = document.getElementById('filter-form');
    const productGrid = document.querySelector('.product-grid');
    const searchBarContainer = document.querySelector('.search-bar');

    // Create Suggestion Box
    const suggestionBox = document.createElement('div');
    suggestionBox.className = 'suggestions-box';
    if (searchBarContainer) searchBarContainer.appendChild(suggestionBox);

    // --- 1. LIVE SUGGESTIONS ---
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.trim();
            if (keyword.length > 0) {
                fetch(window.location.origin + '/CampusKart/products/suggest', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ keyword: keyword })
                })
                    .then(res => res.json())
                    .then(data => {
                        suggestionBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'suggestion-item';
                                div.innerText = item.title;
                                div.onclick = () => {
                                    window.location.href = window.location.origin + '/CampusKart/products/show/' + item.id;
                                };
                                suggestionBox.appendChild(div);
                            });
                        } else {
                            suggestionBox.innerHTML = '<div class="no-match">No matching yet</div>';
                        }
                        suggestionBox.style.display = 'block';
                    });
            } else {
                suggestionBox.style.display = 'none';
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function (e) {
            if (searchBarContainer && !searchBarContainer.contains(e.target)) {
                suggestionBox.style.display = 'none';
            }
        });
    }

    // --- 2. MASTER SEARCH & FILTER FUNCTION ---
    function performSearch() {
        // 1. Get Search Keyword
        const keyword = searchInput ? searchInput.value.trim() : '';

        // 2. Get Filter Values
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        // Add search keyword to params
        if (keyword) params.append('search', keyword);

        // 3. Fetch Results
        fetch(window.location.origin + '/CampusKart/products/search?' + params.toString())
            .then(response => response.text())
            .then(html => {
                productGrid.innerHTML = html;
                if (suggestionBox) suggestionBox.style.display = 'none';
            })
            .catch(err => console.error('Search failed', err));
    }

    // --- 3. EVENT LISTENERS ---

    // A. Main Search Bar Submit
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            performSearch();
        });
    }

    // B. Filter Sidebar Submit
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            performSearch();
        });
    }
});