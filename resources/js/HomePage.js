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

    // Fonction utilitaire pour vérifier si une modale/sidebar est active
    function isAnyModalOrSidebarActive() {
        return (sidebar && sidebar.classList.contains('active')) ||
               (contactSidebar && contactSidebar.classList.contains('active')) ||
               (chatContainer && chatContainer.classList.contains('active')) ||
               (loginModalOverlay && loginModalOverlay.classList.contains('active'));
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

    function openLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.add('active');
            overlay.classList.add('active'); // Ensure overlay is active
            toggleBodyScroll(true);
            // Optionally, close other modals/sidebars if open
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
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

    // --- Featured Apartments Carousel Logic ---
    const featuredApartmentsGrid = document.getElementById('featured-apartments-grid');
    const prevApartmentBtn = document.getElementById('prev-apartment-btn');
    const nextApartmentBtn = document.getElementById('next-apartment-btn');

    if (featuredApartmentsGrid && prevApartmentBtn && nextApartmentBtn) {
        const scrollAmount = featuredApartmentsGrid.querySelector('.property-card-link') ? 
                             featuredApartmentsGrid.querySelector('.property-card-link').offsetWidth + 30 : // Card width + gap
                             300; // Fallback if no cards

        nextApartmentBtn.addEventListener('click', () => {
            featuredApartmentsGrid.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        prevApartmentBtn.addEventListener('click', () => {
            featuredApartmentsGrid.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
    }
});
