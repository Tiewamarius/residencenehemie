document.addEventListener('DOMContentLoaded', () => {
            // --- Script de la modale d'authentification ---
            const authModal = document.getElementById('authModal');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const modalMessage = document.getElementById('modalMessage');
            // Fonctions pour gérer la modale
            window.openAuthModal = function() {
                authModal.classList.remove('hidden');
                // Assurez-vous que l'overlay est actif et le défilement du corps est désactivé
                const overlay = document.getElementById('overlay');
                if (overlay) {
                    overlay.classList.add('active');
                    toggleBodyScroll(true);
                }
            };

            window.closeAuthModal = function() {
                authModal.classList.add('hidden');
                modalMessage.classList.add('hidden');
                // Rétablit le défilement du corps si aucune autre modale n'est active
                const overlay = document.getElementById('overlay');
                if (overlay) {
                    overlay.classList.remove('active');
                    toggleBodyScroll(false);
                }
            };
            
            window.showForm = function(formType) {
                if (formType === 'login') {
                    loginForm.classList.remove('hidden');
                    registerForm.classList.add('hidden');
                    loginTab.classList.add('border-blue-600', 'text-gray-800');
                    loginTab.classList.remove('border-gray-200', 'text-gray-400');
                    registerTab.classList.add('border-gray-200', 'text-gray-400');
                    registerTab.classList.remove('border-blue-600', 'text-gray-800');
                } else {
                    loginForm.classList.add('hidden');
                    registerForm.classList.remove('hidden');
                    registerTab.classList.add('border-blue-600', 'text-gray-800');
                    registerTab.classList.remove('border-gray-200', 'text-gray-400');
                    loginTab.classList.add('border-gray-200', 'text-gray-400');
                    loginTab.classList.remove('border-blue-600', 'text-gray-800');
                }
                modalMessage.classList.add('hidden');
            };

            async function handleLogin(event) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch('/login', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    });

                    const result = await response.json();
                    if (response.ok) {
                        displayMessage('Connexion réussie ! Redirection...', 'success');
                        window.location.reload();
                    } else {
                        displayMessage(result.message || 'Échec de la connexion. Veuillez vérifier vos identifiants.', 'error');
                    }
                } catch (error) {
                    displayMessage('Une erreur est survenue. Veuillez réessayer.', 'error');
                }
            }

            async function handleRegister(event) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch('/register', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(Object.fromEntries(formData))
                    });

                    const result = await response.json();
                    if (response.ok) {
                        displayMessage('Inscription réussie ! Redirection...', 'success');
                        window.location.reload();
                    } else {
                        displayMessage(result.message || 'Échec de l\'inscription. Veuillez réessayer.', 'error');
                    }
                } catch (error) {
                    displayMessage('Une erreur est survenue. Veuillez réessayer.', 'error');
                }
            }

            function displayMessage(message, type) {
                modalMessage.textContent = message;
                modalMessage.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
                if (type === 'error') {
                    modalMessage.classList.add('bg-red-100', 'text-red-700');
                } else {
                    modalMessage.classList.add('bg-green-100', 'text-green-700');
                }
            }

            // --- Votre script existant fusionné ci-dessous ---
            // --- Header Scroll Effect ---
            const header = document.querySelector('.header');
            if (header) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
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

            if (sliderImages.length > 0) {
                sliderImages[currentImageIndex].classList.add('active');
                setInterval(showNextImage, 5000);
            }

            // --- Sidebar (Mobile Menu) Toggle ---
            const sidebar = document.getElementById('sidebar');
            const menuToggleBtn = document.getElementById('menu-toggle-btn');
            const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
            const overlay = document.getElementById('overlay');

            const contactSidebar = document.getElementById('contactsidebar');
            const chatContainer = document.getElementById('chat-container');
            const loginModalOverlay = document.getElementById('login-modal-overlay');
            const searchModalOverlay = document.getElementById('search-modal-overlay');

            function isAnyModalOrSidebarActive() {
                return (sidebar && sidebar.classList.contains('active')) ||
                       (contactSidebar && contactSidebar.classList.contains('active')) ||
                       (chatContainer && chatContainer.classList.contains('active')) ||
                       (loginModalOverlay && loginModalOverlay.classList.contains('active')) ||
                       (searchModalOverlay && searchModalOverlay.classList.contains('active')) ||
                       (!authModal.classList.contains('hidden')); // Ajout de la modale d'authentification
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

            if (menuToggleBtn && sidebar && sidebarCloseBtn && overlay) {
                menuToggleBtn.addEventListener('click', () => {
                    console.log('Menu toggle button clicked!');
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                    toggleBodyScroll(true);
                });

                sidebarCloseBtn.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    toggleBodyScroll(false);
                });

                overlay.addEventListener('click', () => {
                    if (sidebar) sidebar.classList.remove('active');
                    if (contactSidebar) contactSidebar.classList.remove('active');
                    if (chatContainer) chatContainer.classList.remove('active');
                    if (loginModalOverlay) loginModalOverlay.classList.remove('active');
                    if (searchModalOverlay) searchModalOverlay.classList.remove('active');
                    
                    authModal.classList.add('hidden'); // Ferme la modale d'authentification
                    modalMessage.classList.add('hidden'); // Cache le message de la modale d'auth
                    
                    overlay.classList.remove('active');
                    toggleBodyScroll(false);
                });
            }

            // --- Chat Assistant Modal Toggle ---
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

            // Fonctionnalité de chat basique
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

            // Récupérer le bouton et le formulaire par leur ID
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

            // --- Contact Sidebar Toggle ---
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

            // --- Login/Registration Modal Toggle ---
            const openLoginModalBtn = document.getElementById('open-login-modal');
            const openLoginModalTriggers = document.querySelectorAll('.wishlist-icon.open-login-modal-trigger');

            const loginTabBtn = document.getElementById('login-tab-btn');
            const registerTabBtn = document.getElementById('register-tab-btn');
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');

            function showLoginForm() {
                if (loginSection) {
                    loginSection.classList.add('active');
                    registerSection.classList.remove('active');
                    loginTabBtn.classList.add('active');
                    registerTabBtn.classList.remove('active');
                }
            }

            function showRegisterForm() {
                if (registerSection) {
                    registerSection.classList.add('active');
                    loginSection.classList.remove('active');
                    registerTabBtn.classList.add('active');
                    loginTabBtn.classList.remove('active');
                }
            }

            if (loginTabBtn && registerTabBtn && loginSection && registerSection) {
                loginTabBtn.addEventListener('click', showLoginForm);
                registerTabBtn.addEventListener('click', showRegisterForm);
            }

            function openLoginModal() {
                if (loginModalOverlay) {
                    loginModalOverlay.classList.add('active');
                    overlay.classList.add('active');
                    toggleBodyScroll(true);
                    if (sidebar) sidebar.classList.remove('active');
                    if (contactSidebar) contactSidebar.classList.remove('active');
                    if (chatContainer) chatContainer.classList.remove('active');
                    if (searchModalOverlay) searchModalOverlay.classList.remove('active');
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
                    e.preventDefault();
                    openLoginModal();
                });
            }

            openLoginModalTriggers.forEach(trigger => {
                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    openLoginModal();
                });
            });

            const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
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

            // --- Search Modal Toggle (New) ---
            const searchToggleBtn = document.getElementById('search-toggle-btn');
            const searchToggleBtnDesktop = document.getElementById('search-toggle-btn-desktop');
            const searchModalCloseBtn = document.getElementById('search-modal-close-btn');

            function toggleSearchModal() {
                if (searchModalOverlay) {
                    searchModalOverlay.classList.toggle('active');
                    overlay.classList.toggle('active');
                    toggleBodyScroll(searchModalOverlay.classList.contains('active'));
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

            // Sélectionner les éléments du DOM
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
                    
                    if (featureDisplayArea) {
                        featureDisplayArea.style.setProperty('--current-feature-image', `url('${imageDisplay}')`);
                    }
                    if (whyChooseUsSection) {
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
        });