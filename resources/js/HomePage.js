document.addEventListener('DOMContentLoaded', () => {
    // --- Header Scroll Effect ---
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) { // Adjust scroll threshold as needed
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // --- Image Slider (Hero Section Background) ---
    const sliderImages = document.querySelectorAll('.slider-image');
    let currentImageIndex = 0;

    function showNextImage() {
        if (sliderImages.length === 0) return;

        sliderImages[currentImageIndex].classList.remove('active');
        currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
        sliderImages[currentImageIndex].classList.add('active');
    }

    // Start slider if images exist
    if (sliderImages.length > 0) {
        // Ensure the first image is active on load
        sliderImages[currentImageIndex].classList.add('active');
        setInterval(showNextImage, 5000); // Change image every 5 seconds
    }

    // --- Sidebar (Mobile Menu) Toggle ---
    const sidebar = document.getElementById('sidebar');
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const overlay = document.getElementById('overlay'); // Utilisez l'ID pour l'overlay

    // Récupération des autres éléments modaux/sidebars pour la gestion de l'overlay
    const contactSidebar = document.getElementById('contactsidebar');
    const chatContainer = document.getElementById('chat-container');
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const searchModalOverlay = document.getElementById('search-modal-overlay'); // Nouvelle référence

    // Fonction utilitaire pour vérifier si une modale/sidebar est active
    function isAnyModalOrSidebarActive() {
        return (sidebar && sidebar.classList.contains('active')) ||
               (contactSidebar && contactSidebar.classList.contains('active')) ||
               (chatContainer && chatContainer.classList.contains('active')) ||
               (loginModalOverlay && loginModalOverlay.classList.contains('active')) ||
               (searchModalOverlay && searchModalOverlay.classList.contains('active')); // Ajout de la modale de recherche
    }

    // Fonction pour gérer le défilement du corps de la page
    function toggleBodyScroll(disableScroll) {
        if (disableScroll) {
            document.body.style.overflow = 'hidden';
        } else {
            // Rétablit le défilement seulement si aucune autre modale/sidebar n'est active
            if (!isAnyModalOrSidebarActive()) {
                document.body.style.overflow = '';
            }
        }
    }

    if (menuToggleBtn && sidebar && sidebarCloseBtn && overlay) {
        menuToggleBtn.addEventListener('click', () => {
            console.log('Menu toggle button clicked!'); // Log pour le débogage
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        });

        sidebarCloseBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            toggleBodyScroll(false);
            // L'overlay sera géré par son propre écouteur si aucune autre modale n'est ouverte
        });

        // Écouteur global pour l'overlay
        overlay.addEventListener('click', () => {
            // Ferme toutes les modales/sidebars ouvertes
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (searchModalOverlay) searchModalOverlay.classList.remove('active'); // Ferme la modale de recherche
            
            overlay.classList.remove('active'); // Retire l'overlay
            toggleBodyScroll(false); // Rétablit le défilement du corps
        });
    }

    // --- Chat Assistant Modal Toggle ---
    const formOpenBtn = document.getElementById('form-open');
    const formOpenSidebarBtn = document.getElementById('form-open-sidebar'); // Button in sidebar
    const chatCloseBtn = document.getElementById('chat-close-btn');
    const chatInput = document.getElementById('chat_input');
    const sendChatBtn = document.getElementById('send_chat_btn');
    const chatMessages = document.getElementById('chat-messages');

    function toggleChatModal() {
        if (chatContainer) {
            chatContainer.classList.toggle('active');
            overlay.classList.toggle('active');
            toggleBodyScroll(chatContainer.classList.contains('active'));
        }
    }

    if (formOpenBtn) {
        formOpenBtn.addEventListener('click', toggleChatModal);
    }
    if (formOpenSidebarBtn) {
        formOpenSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            if (sidebar) sidebar.classList.remove('active'); // Ferme la sidebar principale
            toggleChatModal(); // Ouvre la modale de chat
        });
    }
    if (chatCloseBtn) {
        chatCloseBtn.addEventListener('click', toggleChatModal);
    }

    // Fonctionnalité de chat basique (à étendre avec l'intégration LLM)
    if (sendChatBtn && chatInput && chatMessages) {
        sendChatBtn.addEventListener('click', () => {
            const messageText = chatInput.value.trim();
            if (messageText !== '') {
                const userMessageDiv = document.createElement('div');
                userMessageDiv.classList.add('message', 'user-message');
                userMessageDiv.textContent = messageText;
                chatMessages.appendChild(userMessageDiv);
                chatInput.value = ''; // Efface l'entrée
                chatMessages.scrollTop = chatMessages.scrollHeight; // Défile vers le bas

                // Simule une réponse du bot (à remplacer par un appel LLM réel plus tard)
                setTimeout(() => {
                    const botMessageDiv = document.createElement('div');
                    botMessageDiv.classList.add('message', 'bot-message');
                    botMessageDiv.textContent = "Je suis un assistant virtuel. Comment puis-je vous aider davantage ?";
                    chatMessages.appendChild(botMessageDiv);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }, 1000);
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendChatBtn.click();
            }
        });
    }

    // --- Contact Sidebar Toggle ---
    const contactOpenBtn = document.getElementById('contact_open_btn');
    const contactOpenSidebarBtn = document.getElementById('contact-open-sidebar-btn'); // Button in sidebar
    const contactSidebarCloseBtn = document.getElementById('contactsidebar_close_btn');

    function toggleContactSidebar() {
        if (contactSidebar) {
            contactSidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            toggleBodyScroll(contactSidebar.classList.contains('active'));
        }
    }

    if (contactOpenBtn) {
        contactOpenBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            toggleContactSidebar();
        });
    }
    if (contactOpenSidebarBtn) {
        contactOpenSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            if (sidebar) sidebar.classList.remove('active'); // Ferme la sidebar principale
            toggleContactSidebar(); // Ouvre la sidebar de contact
        });
    }
    if (contactSidebarCloseBtn) {
        contactSidebarCloseBtn.addEventListener('click', toggleContactSidebar);
    }

    // --- Login/Registration Modal Toggle ---
    const openLoginModalBtn = document.getElementById('open-login-modal'); // Header Favorites link for guests
    const openLoginModalTriggers = document.querySelectorAll('.wishlist-icon.open-login-modal-trigger'); // Wishlist icons for guests

    // New: Tab buttons for login/register
    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

    function showLoginForm() {
        loginSection.classList.add('active');
        registerSection.classList.remove('active');
        loginTabBtn.classList.add('active');
        registerTabBtn.classList.remove('active');
    }

    function showRegisterForm() {
        registerSection.classList.add('active');
        loginSection.classList.remove('active');
        registerTabBtn.classList.add('active');
        loginTabBtn.classList.remove('active');
    }

    if (loginTabBtn && registerTabBtn && loginSection && registerSection) {
        loginTabBtn.addEventListener('click', showLoginForm);
        registerTabBtn.addEventListener('click', showRegisterForm);
    }


    function openLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.add('active');
            overlay.classList.add('active'); // Ensure overlay is active
            toggleBodyScroll(true);
            // Optionally, close other modals/sidebars if open
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (searchModalOverlay) searchModalOverlay.classList.remove('active'); // Ferme la modale de recherche

            // Always show login form by default when opening the modal
            showLoginForm(); 
        }
    }

    function closeLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.remove('active');
            toggleBodyScroll(false);
        }
    }

    if (openLoginModalBtn) {
        openLoginModalBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            openLoginModal();
        });
    }

    openLoginModalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            openLoginModal();
        });
    });

    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    if (loginModalCloseBtn) {
        loginModalCloseBtn.addEventListener('click', closeLoginModal);
    }

    if (loginModalOverlay) {
        loginModalOverlay.addEventListener('click', (e) => {
            // Close if clicking directly on the overlay, not on the modal content itself
            if (e.target === loginModalOverlay) {
                closeLoginModal();
            }
        });
    }

    // --- Search Modal Toggle (New) ---
    const searchToggleBtn = document.getElementById('search-toggle-btn'); // Mobile search button
    const searchToggleBtnDesktop = document.getElementById('search-toggle-btn-desktop'); // Desktop search button
    const searchModalCloseBtn = document.getElementById('search-modal-close-btn');

    function toggleSearchModal() {
        if (searchModalOverlay) {
            searchModalOverlay.classList.toggle('active');
            overlay.classList.toggle('active');
            toggleBodyScroll(searchModalOverlay.classList.contains('active'));
            // Ferme les autres modales/sidebars si elles sont ouvertes
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
        }
    }

    if (searchToggleBtn) {
        searchToggleBtn.addEventListener('click', toggleSearchModal);
    }
    if (searchToggleBtnDesktop) {
        searchToggleBtnDesktop.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSearchModal();
        });
    }

    if (searchModalCloseBtn) {
        searchModalCloseBtn.addEventListener('click', toggleSearchModal);
    }

    if (searchModalOverlay) {
        searchModalOverlay.addEventListener('click', (e) => {
            if (e.target === searchModalOverlay) {
                toggleSearchModal();
            }
        });
    }

    // --- Registration Form Handling (Recoded based on user table) ---
    const registerForm = document.querySelector('#register-section .register-form');
    const registerNameInput = document.getElementById('register-name');
    const registerEmailInput = document.getElementById('register-email');
    const registerPasswordInput = document.getElementById('register-password');
    const registerConfirmPasswordInput = document.getElementById('register-confirm-password');

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission

            const name = registerNameInput.value.trim();
            const email = registerEmailInput.value.trim();
            const password = registerPasswordInput.value;
            const confirmPassword = registerConfirmPasswordInput.value;

            // Basic client-side validation
            if (name === '' || email === '' || password === '' || confirmPassword === '') {
                // In a real application, you'd display an error message to the user
                console.error('Tous les champs d\'inscription sont requis.');
                return;
            }

            if (password !== confirmPassword) {
                console.error('Les mots de passe ne correspondent pas.');
                // In a real app, show a user-friendly error
                return;
            }

            // Here you would typically send this data to your backend for user registration.
            // For now, we'll just log it to the console.
            console.log('Données d\'inscription:', {
                name: name,
                email: email,
                password: password, // In a real app, never send plain password directly. Hash it!
                // role: 'client' // Role would typically be set on the backend or chosen by user if applicable
            });

            // In a real application, after successful registration:
            // 1. Send data to server (e.g., using fetch API)
            // 2. Handle server response (success/error)
            // 3. Close the modal or redirect the user
            // closeLoginModal(); // Example: close modal on success
            // window.location.href = '/dashboard'; // Example: redirect to dashboard
        });
    }

    // --- Featured Apartments Carousel Logic (Removed as per user request) ---
    // const featuredApartmentsGrid = document.getElementById('featured-apartments-grid');
    // const prevApartmentBtn = document.getElementById('prev-apartment-btn');
    // const nextApartmentBtn = document.getElementById('next-apartment-btn');

    // if (featuredApartmentsGrid && prevApartmentBtn && nextApartmentBtn) {
    //     const scrollAmount = featuredApartmentsGrid.querySelector('.property-card-link') ? 
    //                          featuredApartmentsGrid.querySelector('.property-card-link').offsetWidth + 30 : // Card width + gap
    //                          300; // Fallback if no cards

    //     nextApartmentBtn.addEventListener('click', () => {
    //         featuredApartmentsGrid.scrollBy({
    //             left: scrollAmount,
    //             behavior: 'smooth'
    //         });
    //     });

    //     prevApartmentBtn.addEventListener('click', () => {
    //         featuredApartmentsGrid.scrollBy({
    //             left: -scrollAmount,
    //             behavior: 'smooth'
    //         });
    //     });
    // }


 const featureButtons = document.querySelectorAll('.feature-button');
    const featureDisplayArea = document.querySelector('.feature-display-area');
    const featureDisplayTitle = document.getElementById('feature-display-title');
    const featureDisplayText = document.getElementById('feature-display-text');
    const whyChooseUsSection = document.querySelector('.why-choose-us');

    // Define content for each feature.
    // Images are now directly on the button's data attributes in HTML.
    const featuresData = {
        quality: {
            text: "Choisir la Résidence Néhémie, c'est opter pour des espaces de vie pensés dans les moindres détails. Nos appartements 1 et 2 chambres salon sont lumineux, spacieux et offrent un cadre de vie prêt-à-l'emploi, vous permettant de vous sentir chez vous dès les premiers instants."
        },
        flexibility: { // Changed from 'support' to 'flexibility' to match HTML span
            text: "Que vous ayez besoin d'un séjour de courte, moyenne ou longue durée, nous nous adaptons à vos besoins. Nos périodes de location sont à définir ensemble, vous offrant la liberté de planifier votre séjour en toute sérénité."
        },
        security_optimal: { // Changed from 'variety' to 'security_optimal' to match HTML span
            text: "Votre tranquillité est notre priorité absolue. La Résidence Néhémie est une enceinte entièrement sécurisée 24h/24 et 7j/7, avec un contrôle d’accès rigoureux et un personnel dédié à votre protection."
        },
        amenities_comfort: { // Changed from 'security' to 'amenities_comfort' to match HTML span
            text: "Profitez d'un environnement équipé pour faciliter votre quotidien. La résidence dispose de : un parking sécurisé, un groupe électrogène pour une alimentation électrique stable, des connexions internet illimitées ,une réserve d'eau pour votre confort et votre autonomie."
        },
        accessibility: {
            text: "Loin du Brouhaha Urbain, Niché dans une zone paisible, l'emplacement est volontairement éloigné des nuisances sonores. Environnement Verdoyant : La présence d’espaces verts à proximité contribue à créer une atmosphère apaisante et offre des opportunités de détente, du style plein air.Qualité de Vie Supérieure : Vous bénéficierez d'un cadre de vie où le silence et la quiétude sont rois, propice au repos, à la concentration et au bien-être général. C'est l'endroit parfait pour se ressourcer après une journée active."
        }
    };

    function updateFeatureDisplay(featureKey) {
        const data = featuresData[featureKey];
        const activeButton = document.querySelector(`.feature-button[data-feature="${featureKey}"]`);

        if (data && activeButton) {
            // Update title from the active button's text content
            featureDisplayTitle.textContent = activeButton.querySelector('span').textContent;
            featureDisplayText.textContent = data.text;

            const displayImage = activeButton.dataset.imageDisplay;
            const sectionImage = activeButton.dataset.imageSection;

            // Update the CSS variable for the display area's background image
            if (displayImage) {
                featureDisplayArea.style.setProperty('--current-feature-image', `url('${displayImage}')`);
            } else {
                console.warn(`No data-image-display found for feature: ${featureKey}`);
            }

            // Update the CSS variable for the entire section's background image
            if (whyChooseUsSection && sectionImage) {
                whyChooseUsSection.style.setProperty('--why-choose-us-background-image', `url('${sectionImage}')`);
            } else if (whyChooseUsSection) {
                 console.warn(`No data-image-section found for feature: ${featureKey}, using displayImage as fallback.`);
                 // Fallback to displayImage if sectionImage is not defined
                 whyChooseUsSection.style.setProperty('--why-choose-us-background-image', `url('${displayImage}')`);
            }
        } else {
            console.error(`Feature data or active button not found for key: ${featureKey}`);
        }
    }

    featureButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove 'active' class from all buttons
            featureButtons.forEach(btn => btn.classList.remove('active'));
            // Add 'active' class to the clicked button
            button.classList.add('active');

            // Update the display area and section background
            const featureKey = button.dataset.feature;
            updateFeatureDisplay(featureKey);
        });
    });

    // Initialize the display with the active button's content and section background on page load
    const initialActiveButton = document.querySelector('.feature-button.active');
    if (initialActiveButton) {
        updateFeatureDisplay(initialActiveButton.dataset.feature);
    } else {
        // Fallback: if no active button, set a default content and background
        const defaultFeatureKey = 'quality'; // Set your desired default feature
        const defaultButton = document.querySelector(`.feature-button[data-feature="${defaultFeatureKey}"]`);
        if (defaultButton) {
            defaultButton.classList.add('active'); // Activate default button
            updateFeatureDisplay(defaultFeatureKey);
        } else {
            console.error("Default feature button not found.");
            // Even if no button, ensure the section has a default background if JS runs
            if (whyChooseUsSection) {
                whyChooseUsSection.style.setProperty('--why-choose-us-background-image', `url('images/RN2_Appart-4.jpeg')`);
            }
        }
    } 
});


