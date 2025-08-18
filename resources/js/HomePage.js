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
    // --- Modal de Connexion / Inscription ---
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
            showLoginForm(); // Affiche la section de connexion par défaut
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
    // Récupérer le jeton CSRF à partir de la balise meta
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : null;

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
            e.preventDefault();
            displayFormMessage(loginForm, "Connexion en cours...", 'info');

            const formData = new FormData(loginForm);

            // Ajouter le jeton CSRF aux en-têtes de la requête
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Ajout du jeton CSRF
            };

            try {
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: headers
                });

                // Si le serveur a déclenché une redirection, on la gère ici
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                const result = await response.json();

                if (response.ok) {
                    displayFormMessage(loginForm, result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 1500);
                } else {
                    // Si la réponse n'est pas OK, le statut peut être 419, 422, etc.
                    // On gère les erreurs de validation ou d'autres erreurs du serveur
                    const firstError = (result.errors && Object.keys(result.errors).length > 0) ?
                        result.errors[Object.keys(result.errors)[0]][0] :
                        result.message || "Une erreur est survenue. Veuillez réessayer.";
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

            // Ajouter le jeton CSRF aux en-têtes de la requête
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Ajout du jeton CSRF
            };

            try {
                const response = await fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: headers
                });

                // Si le serveur a déclenché une redirection, on la gère ici
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                const result = await response.json();

                if (response.ok) {
                    displayFormMessage(registerForm, result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 1500);
                } else {
                    const firstError = (result.errors && Object.keys(result.errors).length > 0) ?
                        result.errors[Object.keys(result.errors)[0]][0] :
                        result.message || "Une erreur est survenue. Veuillez réessayer.";
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
            if (imageDisplay) {
                featureDisplayArea.style.setProperty('--current-feature-image', `url('${imageDisplay}')`);
            }
            if (imageSection) {
                whyChooseUsSection.style.setProperty('--why-choose-us-background-image', `url('${imageSection}')`);
            }
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

    // ===========================================================
    // --- Ouverture de la modale via l'URL ---
    // ===========================================================
    const currentPath = window.location.pathname;

    if (currentPath.startsWith('/login')) {
        openLoginModal();
    } else if (currentPath.startsWith('/register')) {
        openLoginModal();
        showRegisterForm();
    }


    // Script Search
    const searchForms = document.getElementById('search-form-opens');
    const propertiesContainer = document.getElementById('properties-container');
    const resultsHeaderContainer = document.getElementById('results-header-container');
    const targetSection = document.getElementById('appartments');

    function createApartmentCard(apartment) {
        const imageUrl = apartment.featuredImage ? apartment.featuredImage : 'https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';
        const detailRoute = `/residences/detailsAppart/${apartment.id}`;
        const isSuperhost = apartment.is_superhost ?
            `<span class="absolute top-2 left-2 bg-white text-gray-900 font-semibold px-2 py-1 rounded-full text-xs shadow-md">Superhôte</span>` : '';

        // Déterminez si l'appartement est en favoris pour l'icône de cœur
        const heartClass = apartment.is_favorited ? 'fas' : 'far';

        let stars = '';
        const fullStars = Math.floor(apartment.rating);
        const hasHalfStar = apartment.rating % 1 > 0;
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }

        return `
            <a href="${detailRoute}" class="property-card-link">
                <div class="property-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl relative transition-all duration-300 ease-in-out">
                    <div class="property-image h-48 bg-gray-200 relative">
                        ${isSuperhost}
                        <img src="${imageUrl}" alt="${apartment.nom}" class="w-full h-full object-cover">
                        <span class="wishlist-icon" data-residence-id="${apartment.id}"><i class="${heartClass} fa-heart"></i></span>
                    </div>
                    <div class="property-details p-4">
                        <div class="property-review flex items-center mb-2">
                            <p class="review-stars flex items-center text-yellow-400 text-sm">
                                ${stars}
                                <span class="text-gray-600 ml-2">(${apartment.rating}/5)</span>
                            </p>
                        </div>
                        <h3 class="font-semibold text-gray-800 text-lg">${apartment.nom.length > 30 ? apartment.nom.substring(0, 30) + '...' : apartment.nom}</h3>
                        <p class="property-location text-gray-500 text-sm mt-1">${apartment.ville}</p>
                        <p class="property-price font-bold text-gray-900 mt-2">À partir de ${apartment.prix_min.toLocaleString('fr-FR')} XOF</p>
                    </div>
                </div>
            </a>
        `;
    }

    function renderProperties(apartments) {
        propertiesContainer.style.opacity = '0';
        setTimeout(() => {
            propertiesContainer.innerHTML = '';
            if (apartments.length > 0) {
                apartments.forEach(apartment => {
                    propertiesContainer.innerHTML += createApartmentCard(apartment);
                });
                resultsHeaderContainer.innerHTML = `
                    <h2 class="section-title text-3xl font-bold text-gray-900 text-center">${apartments.length} logements disponibles</h2>
                    <p class="section-description mt-2 text-lg text-gray-600 text-center mb-8">Découvrez les résultats de votre recherche.</p>
                `;
            } else {
                propertiesContainer.innerHTML = '<p class="text-gray-600 col-span-full text-center text-xl p-8">Aucun appartement disponible pour cette recherche.</p>';
                resultsHeaderContainer.innerHTML = `
                    <h2 class="section-title text-3xl font-bold text-gray-900 text-center">Aucun résultat trouvé</h2>
                    <p class="section-description mt-2 text-lg text-gray-600 text-center mb-8">Veuillez essayer une autre recherche.</p>
                `;
            }
            propertiesContainer.style.opacity = '1';
        }, 500);
    }

    searchForms.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Récupération des valeurs du formulaire
        const address = document.getElementById('address').value;
        const arrivee = document.getElementById('arrivee').value;
        const depart = document.getElementById('depart').value;
        const adultes = document.getElementById('adultes').value;
        const enfants = document.getElementById('enfants').value;

        // Afficher l'état de chargement
        propertiesContainer.innerHTML = '<div class="col-span-full text-center text-blue-600 font-medium">Recherche en cours...</div>';
        resultsHeaderContainer.innerHTML = `
            <h2 class="section-title text-3xl font-bold text-gray-900 text-center">Recherche en cours...</h2>
            <p class="section-description mt-2 text-lg text-gray-600 text-center mb-8">Veuillez patienter pendant que nous trouvons les meilleurs logements pour vous.</p>
        `;

        // Construire l'objet de données à envoyer
        const searchData = {
            address: address,
            arrivee: arrivee,
            depart: depart,
            adultes: adultes,
            enfants: enfants
        };

        try {
            // Effectuer une requête POST vers votre API
            const response = await fetch('/api/search-apartments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(searchData)
            });

            // Vérifier si la réponse est OK
            if (!response.ok) {
                throw new Error('Erreur réseau ou réponse de l\'API non valide.');
            }

            // Récupérer les données JSON de la réponse
            const apartments = await response.json();

            // Afficher les résultats
            renderProperties(apartments);

        } catch (error) {
            // Gérer les erreurs de la requête
            console.error('Erreur lors de la recherche des appartements :', error);
            propertiesContainer.innerHTML = `<p class="text-red-500 col-span-full text-center text-lg p-8">Une erreur est survenue lors de la recherche. Veuillez réessayer.</p>`;
            resultsHeaderContainer.innerHTML = `<h2 class="section-title text-3xl font-bold text-red-500 text-center">Erreur</h2>`;
        }
    });

    // =========================================================
    // --- NOUVEAU : Fonctionnalité de gestion des favoris ---
    // =========================================================

    // J'utilise une délégation d'événements pour les icônes de favoris,
    // car les cartes sont ajoutées dynamiquement au DOM.
    document.body.addEventListener('click', async (e) => {
        // Cibler l'icône de cœur ou son parent avec la classe 'wishlist-icon'
        const wishlistIcon = e.target.closest('.wishlist-icon');
        if (!wishlistIcon) {
            return; // Si l'élément cliqué n'est pas un icône de favori, on ne fait rien
        }

        e.preventDefault();
        e.stopPropagation();

        const residenceId = wishlistIcon.dataset.residenceId;
        const icon = wishlistIcon.querySelector('i');
        const token = document.querySelector('meta[name="csrf-token"]').content;

        if (!token) {
            console.error('Erreur: Jeton CSRF non trouvé.');
            // Gérer l'erreur, par exemple, en affichant un message à l'utilisateur
            return;
        }

        try {
            const response = await fetch(`/toggle-favori/${residenceId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({}) // Un corps vide peut être envoyé si le backend s'attend à du JSON
            });

            const result = await response.json();

            if (response.ok) {
                if (result.status === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    console.log(result.message);
                } else if (result.status === 'removed') {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    console.log(result.message);
                }
            } else {
                if (response.status === 401) {
                    // Si l'utilisateur n'est pas connecté, on déclenche l'ouverture de la modale de connexion
                    openLoginModal();
                    console.warn('Vous devez être connecté pour ajouter des favoris.');
                } else {
                    console.error('Erreur du serveur:', result.message || 'Une erreur est survenue.');
                }
            }
        } catch (error) {
            console.error('Erreur lors de la requête de favoris:', error);
        }
    });
});
