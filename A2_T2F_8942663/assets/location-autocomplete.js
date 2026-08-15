document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.autocomplete[data-role="location-search"]').forEach(function (container) {
        const searchBox = container.querySelector('.location-search-input');
        const hiddenId = container.querySelector('.location-hidden-id');
        const suggestions = container.querySelector('.suggestions');
        const form = container.closest('form');
        let debounceTimer = null;
        let activeResults = [];

        function renderSuggestions(results) {
            activeResults = results;
            if (results.length === 0) {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
                return;
            }
            suggestions.innerHTML = results.map(function (loc, i) {
                return '<div class="suggestion-item" data-index="' + i + '">' +
                    '<strong>' + loc.description + '</strong>' +
                    '<span>' + loc.num_studios + ' studio' + (loc.num_studios > 1 ? 's' : '') +
                    ' &middot; $' + loc.cost_per_hour.toFixed(2) + '/hr</span>' +
                    '</div>';
            }).join('');
            suggestions.style.display = 'block';
        }

        function selectLocation(loc) {
            hiddenId.value = loc.location_id;
            searchBox.value = loc.description;
            suggestions.style.display = 'none';
        }

        searchBox.addEventListener('input', function () {
            hiddenId.value = '';
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
                    alert('Please select a location from the suggestions list.');
                }
            });
        }
    });
});
