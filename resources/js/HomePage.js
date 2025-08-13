// Attendre que le DOM soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', () => {

    // ======================================
    // --- Effet de défilement de l'en-tête ---
    // ======================================
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) { // Ajustez le seuil de défilement selon vos besoins
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // ==================================================
    // --- Carrousel d'images (arrière-plan de l'héro) ---
    // ==================================================
    const sliderImages = document.querySelectorAll('.slider-image');
    let currentImageIndex = 0;

    function showNextImage() {
        if (sliderImages.length === 0) return;

        sliderImages[currentImageIndex].classList.remove('active');
        currentImageIndex = (currentImageIndex + 1) % sliderImages.length;
        sliderImages[currentImageIndex].classList.add('active');
    }

    if (sliderImages.length > 0) {
        sliderImages[currentImageIndex].classList.add('active');
        setInterval(showNextImage, 5000);
    }

    // =========================================================
    // --- Fonctions utilitaires de gestion de l'état global ---
    // =========================================================
    const overlay = document.getElementById('overlay');
    const sidebar = document.getElementById('sidebar');
    const contactSidebar = document.getElementById('contactsidebar');
    const chatContainer = document.getElementById('chat-container');
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const searchModalOverlay = document.getElementById('search-modal-overlay');

    function isAnyModalOrSidebarActive() {
        return (sidebar && sidebar.classList.contains('active')) ||
               (contactSidebar && contactSidebar.classList.contains('active')) ||
               (chatContainer && chatContainer.classList.contains('active')) ||
               (loginModalOverlay && loginModalOverlay.classList.contains('active')) ||
               (searchModalOverlay && searchModalOverlay.classList.contains('active'));
    }

    function toggleBodyScroll(disableScroll) {
        if (disableScroll) {
            document.body.style.overflow = 'hidden';
        } else {
            if (!isAnyModalOrSidebarActive()) {
                document.body.style.overflow = '';
            }
        }
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (searchModalOverlay) searchModalOverlay.classList.remove('active');
            
            overlay.classList.remove('active');
            toggleBodyScroll(false);
        });
    }

    // =======================================
    // --- Sidebar (Menu Mobile) et Overlay ---
    // =======================================
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');

    if (menuToggleBtn && sidebar && sidebarCloseBtn) {
        menuToggleBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        });

        sidebarCloseBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            if (!isAnyModalOrSidebarActive()) {
                overlay.classList.remove('active');
                toggleBodyScroll(false);
            }
        });
    }

    // =======================================
    // --- Modal de l'Assistant de Chat ---
    // =======================================
    const formOpenBtn = document.getElementById('form-open');
    const formOpenSidebarBtn = document.getElementById('form-open-sidebar');
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
            e.preventDefault();
            if (sidebar) sidebar.classList.remove('active');
            toggleChatModal();
        });
    }
    if (chatCloseBtn) {
        chatCloseBtn.addEventListener('click', toggleChatModal);
    }

    if (sendChatBtn && chatInput && chatMessages) {
        sendChatBtn.addEventListener('click', () => {
            const messageText = chatInput.value.trim();
            if (messageText !== '') {
                const userMessageDiv = document.createElement('div');
                userMessageDiv.classList.add('message', 'user-message');
                userMessageDiv.textContent = messageText;
                chatMessages.appendChild(userMessageDiv);
                chatInput.value = '';
                chatMessages.scrollTop = chatMessages.scrollHeight;

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

    // ======================================
    // --- Toggle du formulaire de recherche ---
    // ======================================
    const openButton = document.getElementById('button-opens');
    const closeButton = document.getElementById('close-buttons');
    const searchForm = document.getElementById('search-form-opens');

    if (openButton && closeButton && searchForm) {
        openButton.addEventListener('click', () => {
            searchForm.style.display = 'flex';
            openButton.style.display = 'none';
            closeButton.style.display = 'flex';
        });

        closeButton.addEventListener('click', () => {
            searchForm.style.display = 'none';
            openButton.style.display = 'flex';
            closeButton.style.display = 'none';
        });
    }

    // ======================================
    // --- Sidebar de Contact ---
    // ======================================
    const contactOpenBtn = document.getElementById('contact_open_btn');
    const contactOpenSidebarBtn = document.getElementById('contact-open-sidebar-btn');
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
            e.preventDefault();
            toggleContactSidebar();
        });
    }
    if (contactOpenSidebarBtn) {
        contactOpenSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (sidebar) sidebar.classList.remove('active');
            toggleContactSidebar();
        });
    }
    if (contactSidebarCloseBtn) {
        contactSidebarCloseBtn.addEventListener('click', toggleContactSidebar);
    }

    // ==================================================
    // --- Modal de Connexion / Inscription (Corrigé et mis à jour) ---
    // ==================================================
    const openLoginModalBtn = document.getElementById('open-login-modal-btn');
    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    function showLoginForm() {
        if (loginSection) loginSection.classList.add('active');
        if (registerSection) registerSection.classList.remove('active');
        if (loginTabBtn) loginTabBtn.classList.add('active');
        if (registerTabBtn) registerTabBtn.classList.remove('active');
    }

    function showRegisterForm() {
        if (registerSection) registerSection.classList.add('active');
        if (loginSection) loginSection.classList.remove('active');
        if (registerTabBtn) registerTabBtn.classList.add('active');
        if (loginTabBtn) loginTabBtn.classList.remove('active');
    }

    function openLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
            showLoginForm();
        }
    }

    function closeLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.remove('active');
            if (!isAnyModalOrSidebarActive()) {
                overlay.classList.remove('active');
                toggleBodyScroll(false);
            }
        }
    }

    if (openLoginModalBtn) {
        openLoginModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openLoginModal();
        });
    }

    if (loginModalCloseBtn) {
        loginModalCloseBtn.addEventListener('click', closeLoginModal);
    }
    
    if (loginModalOverlay) {
        loginModalOverlay.addEventListener('click', (e) => {
            if (e.target === loginModalOverlay) {
                closeLoginModal();
            }
        });
    }

    if (loginTabBtn) {
        loginTabBtn.addEventListener('click', showLoginForm);
    }
    
    if (registerTabBtn) {
        registerTabBtn.addEventListener('click', showRegisterForm);
    }

    // ======================================================
    // --- GESTION DE LA SOUMISSION DES FORMULAIRES (AJAX) ---
    // ======================================================
    // Fonction pour afficher des messages d'erreur ou de succès
    function displayFormMessage(formElement, message, type) {
        // Crée ou trouve un élément pour les messages
        let messageElement = formElement.querySelector('.form-message');
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.classList.add('form-message');
            formElement.prepend(messageElement);
        }
        messageElement.textContent = message;
        messageElement.style.color = type === 'success' ? 'green' : 'red';
        messageElement.style.display = 'block';
    }

    // Soumission du formulaire de connexion
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            // Empêcher le comportement par défaut du formulaire (rechargement de la page)
            e.preventDefault();

            // Afficher un message de chargement
            displayFormMessage(loginForm, "Connexion en cours...", 'info');
            
            const formData = new FormData(loginForm);
            
            try {
                // Envoyer les données au serveur Laravel
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Important pour Laravel
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Si la réponse est un succès (statut 200)
                    displayFormMessage(loginForm, result.message, 'success');
                    // Rediriger l'utilisateur ou fermer la modale après un court délai
                    setTimeout(() => {
                        window.location.href = result.redirect || '/'; // Redirection
                    }, 1500);
                } else {
                    // Si la réponse contient des erreurs (ex: statut 422 - validation)
                    const errors = result.errors;
                    const firstError = errors[Object.keys(errors)[0]][0];
                    displayFormMessage(loginForm, firstError, 'error');
                }
            } catch (error) {
                console.error('Erreur de connexion:', error);
                displayFormMessage(loginForm, "Une erreur est survenue. Veuillez réessayer.", 'error');
            }
        });
    }

    // Soumission du formulaire d'inscription
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            displayFormMessage(registerForm, "Inscription en cours...", 'info');

            const formData = new FormData(registerForm);

            try {
                const response = await fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                const result = await response.json();

                if (response.ok) {
                    displayFormMessage(registerForm, result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 1500);
                } else {
                    const errors = result.errors;
                    const firstError = errors[Object.keys(errors)[0]][0];
                    displayFormMessage(registerForm, firstError, 'error');
                }
            } catch (error) {
                console.error('Erreur d\'inscription:', error);
                displayFormMessage(registerForm, "Une erreur est survenue. Veuillez réessayer.", 'error');
            }
        });
    }

    // ======================================
    // --- Modal de Recherche ---
    // ======================================
    const searchToggleBtn = document.getElementById('search-toggle-btn');
    const searchToggleBtnDesktop = document.getElementById('search-toggle-btn-desktop');
    const searchModalCloseBtn = document.getElementById('search-modal-close-btn');

    function toggleSearchModal() {
        if (searchModalOverlay) {
            searchModalOverlay.classList.toggle('active');
            overlay.classList.toggle('active');
            toggleBodyScroll(searchModalOverlay.classList.contains('active'));
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

    // ==================================================
    // --- Gestion des boutons de fonctionnalités ---
    // ==================================================
    const buttons = document.querySelectorAll('.feature-button');
    const featureDisplayArea = document.querySelector('.feature-display-area');
    const featureDisplayTitle = document.getElementById('feature-display-title');
    const featureDisplayText = document.getElementById('feature-display-text');
    const whyChooseUsSection = document.querySelector('.why-choose-us');

    const featuresData = {
        'quality': {
            title: "Votre Bien-Être",
            text: "Nous nous engageons à offrir des propriétés de la plus haute qualité, garantissant confort et satisfaction à chaque séjour."
        },
        'flexibility': {
            title: "La Flexibilité",
            text: "Que vous ayez besoin d'un séjour de courte, moyenne ou longue durée, nous nous adaptons à vos besoins. Nos périodes de location sont à définir ensemble, vous offrant la liberté de planifier votre séjour en toute sérénité."
        },
        'security_optimal': {
            title: "Sécurité Optimale",
            text: "Votre tranquillité est notre priorité absolue. La Résidence Néhémie est une enceinte entièrement sécurisée 24h/24 et 7j/7, avec un contrôle d’accès rigoureux et un personnel dédié à votre protection."
        },
        'amenities_comfort': {
            title: "Commodités & Confort",
            text: "Profitez d'un environnement équipé pour faciliter votre quotidien. La résidence dispose de : un parking sécurisé, un groupe électrogène pour une alimentation électrique stable, des connexions internet illimitées, une réserve d'eau pour votre confort et votre autonomie."
        },
        'accessibility': {
            title: "Emplacement idéal",
            text: "Loin du Brouhaha Urbain, Niché dans une zone paisible, l'emplacement est volontairement éloigné des nuisances sonores. Environnement Verdoyant : La présence d’espaces verts à proximité contribue à créer une atmosphère apaisante et offre des opportunités de détente, du style plein air.Qualité de Vie Supérieure : Vous bénéficierez d'un cadre de vie où le silence et la quiétude sont rois, propice au repos, à la concentration et au bien-être général. C'est l'endroit parfait pour se ressourcer après une journée active."
        }
    };

    function updateFeatureDisplay(featureKey, imageDisplay, imageSection) {
        const data = featuresData[featureKey];
        if (data) {
            featureDisplayTitle.textContent = data.title;
            featureDisplayText.textContent = data.text;
            featureDisplayArea.style.setProperty('--current-feature-image', `url('${imageDisplay}')`);
            whyChooseUsSection.style.setProperty('--why-choose-us-background-image', `url('${imageSection}')`);
        }
    }

    function handleInteraction(button) {
        buttons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        const featureKey = button.dataset.feature;
        const imageDisplay = button.dataset.imageDisplay;
        const imageSection = button.dataset.imageSection;
        updateFeatureDisplay(featureKey, imageDisplay, imageSection);
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            handleInteraction(button);
        });
        
        button.addEventListener('mouseenter', () => {
            handleInteraction(button);
        });
    });

    const initialActiveButton = document.querySelector('.feature-button.active');
    if (initialActiveButton) {
        const featureKey = initialActiveButton.dataset.feature;
        const imageDisplay = initialActiveButton.dataset.imageDisplay;
        const imageSection = initialActiveButton.dataset.imageSection;
        updateFeatureDisplay(featureKey, imageDisplay, imageSection);
    }
});
