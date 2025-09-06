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
    updateFormSection.style.display = 'none';

    // 1. Tab switching logic
    tabs.forEach(tab => {
        tab.addEventListener("click", e => {
            e.preventDefault();

            // Deactivate all tabs and content
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));

            // Activate the selected tab and its content
            tab.classList.add("active");
            const target = document.getElementById(tab.dataset.tab);
            if (target) {
                target.classList.add("active");
                
                // IMPORTANT: When switching tabs, explicitly ensure the profile edit form is hidden
                // and the view is reset to the default profile info.
                if (target.id !== 'about-me') {
                    // Hide the form and reset the button if not on the 'about-me' tab
                    updateFormSection.style.display = 'none';
                    editButton.textContent = 'Modifier';
                    profileInfoSection.style.display = 'block';
                    profileReminderSection.style.display = 'block';
                    profileCommentsSection.style.display = 'block';
                }
            }
        });
    });

    // 2. Profile edit button logic
    editButton.addEventListener('click', function(event) {
        event.preventDefault();

        // Toggle visibility of the "about-me" sub-sections
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
    });

    // Star rating logic (remains the same as it's separate)
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
});