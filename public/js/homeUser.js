document.addEventListener("DOMContentLoaded", () => {
    const tabs = document.querySelectorAll(".profile-nav-item");
    const contents = document.querySelectorAll(".tab-content");

    // Profile Edit Elements
    const editButton = document.querySelector('.tab-header .btn-light');
    const profileInfoSection = document.querySelector('.profils');
    const profileReminderSection = document.querySelector('.profile-reminder');
    const profileCommentsSection = document.querySelector('.profile-comments');
    const updateFormSection = document.querySelector('.formulaire_update');
    
    // Set the initial state for the form
    if (updateFormSection) {
        updateFormSection.style.display = 'none';
    }

    // New elements for AJAX pagination, filtering, and search
    const reservationsContainer = document.getElementById('reservations-container');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');

    // 1. Tab switching logic
    tabs.forEach(tab => {
        tab.addEventListener("click", e => {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));

            tab.classList.add("active");
            const target = document.getElementById(tab.dataset.tab);
            if (target) {
                target.classList.add("active");
                
                if (target.id !== 'about-me' && updateFormSection) {
                    updateFormSection.style.display = 'none';
                    if (editButton) editButton.textContent = 'Modifier';
                    if (profileInfoSection) profileInfoSection.style.display = 'block';
                    if (profileReminderSection) profileReminderSection.style.display = 'block';
                    if (profileCommentsSection) profileCommentsSection.style.display = 'block';
                }
            }
        });
    });

    // 2. Profile edit button logic
    if (editButton) {
        editButton.addEventListener('click', function(event) {
            event.preventDefault();

            if (updateFormSection && profileInfoSection && profileReminderSection && profileCommentsSection) {
                if (updateFormSection.style.display === 'none') {
                    profileInfoSection.style.display = 'none';
                    profileReminderSection.style.display = 'none';
                    profileCommentsSection.style.display = 'none';
                    
                    updateFormSection.style.display = 'block';
                    editButton.textContent = 'Annuler';
                } else {
                    profileInfoSection.style.display = 'block';
                    profileReminderSection.style.display = 'block';
                    profileCommentsSection.style.display = 'block';
                    
                    updateFormSection.style.display = 'none';
                    editButton.textContent = 'Modifier';
                }
            }
        });
    }

    // 3. Star rating logic (assuming it's still needed)
    document.querySelectorAll('.star-rating').forEach(rating => {
        const input = document.getElementById(rating.dataset.target);
        rating.querySelectorAll('i').forEach((star, index) => {
            star.addEventListener('click', () => {
                const value = index + 1;
                input.value = value;
                rating.querySelectorAll('i').forEach(s => {
                    s.classList.remove('fas');
                    s.classList.add('far');
                });
                for (let i = 0; i < value; i++) {
                    rating.querySelectorAll('i')[i].classList.remove('far');
                    rating.querySelectorAll('i')[i].classList.add('fas');
                }
            });
            star.addEventListener('mouseover', () => {
                rating.querySelectorAll('i').forEach((s, i) => {
                    s.classList.toggle('fas', i <= index);
                    s.classList.toggle('far', i > index);
                });
            });
            rating.addEventListener('mouseleave', () => {
                const value = input.value;
                rating.querySelectorAll('i').forEach((s, i) => {
                    s.classList.toggle('fas', i < value);
                    s.classList.toggle('far', i >= value);
                });
            });
        });
    });

    // --- New AJAX Logic for Pagination, Filtering, and Search ---

    // Function to load data via AJAX
    function fetchReservations(url) {
        if (!reservationsContainer) return;

        // Add a loading indicator
        reservationsContainer.innerHTML = '<div class="loading-indicator" style="text-align: center; padding: 20px;">Chargement en cours...</div>';

        // Use the Fetch API for a more modern approach than Axios
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Identifies the request as AJAX to Laravel
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the container with the new HTML
            reservationsContainer.innerHTML = data.html;
            
            // Re-attach the event listeners after the DOM is updated
            setupPaginationListeners();
        })
        .catch(error => {
            console.error('Erreur lors du chargement des réservations:', error);
            reservationsContainer.innerHTML = '<div class="error-message" style="text-align: center; padding: 20px; color: red;">Erreur de chargement. Veuillez réessayer.</div>';
        });
    }

    // Intercept clicks on pagination links
    // Intercept clicks on pagination links
function setupPaginationListeners() {
    if (reservationsContainer) {
        const paginationLinks = reservationsContainer.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent page refresh
                
                // Get the base URL from the clicked link
                const url = new URL(this.getAttribute('href'));
                
                // Get the current search and status parameters from the window's URL
                const currentSearchParams = new URLSearchParams(window.location.search);
                const searchTerm = currentSearchParams.get('search');
                const status = currentSearchParams.get('statut');

                // Add the existing search and status parameters to the new URL if they exist
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                }
                if (status) {
                    url.searchParams.set('statut', status);
                }

                // Call the fetch function with the updated URL
                fetchReservations(url.toString());
            });
        });
    }
}

    // Handle filtering and search
    function applyFilters() {
        const baseUrl = new URL(window.location.origin + window.location.pathname);
        
        // Build query parameters
        const params = new URLSearchParams();
        const searchTerm = searchInput.value.trim();
        const status = statusFilter.value;

        if (searchTerm) {
            params.set('search', searchTerm);
        }
        if (status && status !== 'all') {
            params.set('statut', status);
        }
        
        // Update URL and fetch data
        const newUrl = `${baseUrl.toString()}?${params.toString()}`;
        fetchReservations(newUrl);
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }

    // Initialize pagination listeners on first page load
    setupPaginationListeners();
});