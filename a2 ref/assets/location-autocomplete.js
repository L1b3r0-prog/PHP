// Powers any .autocomplete[data-role="location-search"] block on the page.
// Expects inside it: input.location-search-input, input.location-hidden-id, div.suggestions
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.autocomplete[data-role="location-search"]').forEach(function (container) {
        const searchBox = container.querySelector('.location-search-input');
        const hiddenId = container.querySelector('.location-hidden-id');
        const suggestions = container.querySelector('.suggestions');
        const form = container.closest('form');
        let debounceTimer = null;
        let activeResults = [];

        // Inline error element, styled like the rest of the site's alerts,
        // shown next to the field instead of a native alert() popup.
        let errorEl = container.querySelector('.form-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'form-error';
            container.insertAdjacentElement('afterend', errorEl);
        }
        function showError(message) {
            errorEl.textContent = message;
            errorEl.classList.add('visible');
        }
        function clearError() {
            errorEl.textContent = '';
            errorEl.classList.remove('visible');
        }

        function renderSuggestions(results) {
            activeResults = results;
            if (results.length === 0) {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
                return;
            }
            suggestions.innerHTML = results.map(function (loc, i) {
                const studioNote = loc.matched_studio ? (' &middot; matched studio: ' + loc.matched_studio) : '';
                return '<div class="suggestion-item" data-index="' + i + '">' +
                    '<strong>' + loc.description + '</strong>' +
                    '<span>' + loc.num_studios + ' studio' + (loc.num_studios > 1 ? 's' : '') +
                    ' &middot; $' + loc.cost_per_hour.toFixed(2) + '/hr' + studioNote + '</span>' +
                    '</div>';
            }).join('');
            suggestions.style.display = 'block';
        }

        function selectLocation(loc) {
            hiddenId.value = loc.location_id;
            searchBox.value = loc.description;
            suggestions.style.display = 'none';
            clearError();
        }

        searchBox.addEventListener('input', function () {
            hiddenId.value = '';
            clearError();
            const term = searchBox.value.trim();
            clearTimeout(debounceTimer);
            if (term === '') {
                renderSuggestions([]);
                return;
            }
            debounceTimer = setTimeout(function () {
                fetch('ajax_location_search.php?q=' + encodeURIComponent(term))
                    .then(function (res) { return res.json(); })
                    .then(renderSuggestions)
                    .catch(function () { renderSuggestions([]); });
            }, 250);
        });

        suggestions.addEventListener('click', function (e) {
            const item = e.target.closest('.suggestion-item');
            if (!item) return;
            selectLocation(activeResults[parseInt(item.dataset.index, 10)]);
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.autocomplete')) {
                suggestions.style.display = 'none';
            }
        });

        if (form) {
            form.addEventListener('submit', function (e) {
                if (!hiddenId.value) {
                    e.preventDefault();
                    showError('Please select a location from the suggestions list.');
                    searchBox.focus();
                }
            });
        }
    });
});
