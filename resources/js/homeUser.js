document.addEventListener('DOMContentLoaded', () => {
    // --- Dropdown Menu Toggle for User Avatar ---
    const userDropdownButton = document.querySelector('.header .group > button');
    const userDropdownMenu = document.querySelector('.header .group .absolute');

    if (userDropdownButton && userDropdownMenu) {
        userDropdownButton.addEventListener('click', () => {
            // Toggle visibility classes
            userDropdownMenu.classList.toggle('opacity-0');
            userDropdownMenu.classList.toggle('invisible');
            userDropdownMenu.classList.toggle('opacity-100');
            userDropdownMenu.classList.toggle('visible');
        });

        // Close dropdown if clicked outside
        document.addEventListener('click', (event) => {
            if (!userDropdownButton.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                userDropdownMenu.classList.remove('opacity-100', 'visible');
                userDropdownMenu.classList.add('opacity-0', 'invisible');
            }
        });
    }

    // --- Profile Navigation Active State (Client-side example) ---
    // This assumes you want to handle active state purely client-side.
    // If your backend renders the page and sets the active class, this might not be needed.
    const profileNavItems = document.querySelectorAll('.profile-nav-item');

    profileNavItems.forEach(item => {
        item.addEventListener('click', (e) => {
            // Prevent default link behavior if you're handling routing client-side
            // e.preventDefault(); 

            // Remove 'active' from all items
            profileNavItems.forEach(nav => nav.classList.remove('active'));

            // Add 'active' to the clicked item
            item.classList.add('active');

            // In a real application, you would load content dynamically here
            // based on the clicked navigation item (e.g., using AJAX/Fetch API)
            console.log(`Navigation item clicked: ${item.textContent.trim()}`);
        });
    });

    // --- Placeholder for "Modifier" button functionality ---
    const modifyProfileLink = document.querySelector('a[href="{{ route("profile.edit") }}"]');
    if (modifyProfileLink) {
        modifyProfileLink.addEventListener('click', (e) => {
            // e.preventDefault(); // Uncomment if you want to handle navigation via JS
            console.log('Bouton "Modifier" cliqué. Logic d\'édition du profil à implémenter ou redirection.');
            // Example: window.location.href = '{{ route("profile.edit") }}';
            // Or open a modal for in-page editing
        });
    }

    // --- Placeholder for "Commencer" button functionality (Complete Profile) ---
    const completeProfileButton = document.querySelector('.bg-pink-600');
    if (completeProfileButton) {
        completeProfileButton.addEventListener('click', () => {
            // Logic to guide the user through completing their profile
            console.log('Bouton "Commencer" cliqué. Logic de complétion du profil à implémenter.');
            // Example: window.location.href = '/profile/onboarding';
        });
    }

    // --- Placeholder for "Déconnexion" link ---
    // This assumes your HTML already has a form for logout as seen in other immersives.
    // If not, you'd need to create one or use a direct fetch request.
    const logoutLink = document.querySelector('.dropdown-menu a[href="#"][onclick*="logout-form"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('logout-form').submit(); // Assuming a form with ID 'logout-form' exists
            console.log('Déconnexion cliquée.');
        });
    }
});
