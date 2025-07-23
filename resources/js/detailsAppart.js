document.addEventListener('DOMContentLoaded', () => {
    // --- Header Scroll Effect (Unified with HomePage) ---
    const header = document.querySelector('.header');
    if (header) {
        // Pour la page de détails, le header est toujours blanc et a une ombre,
        // donc l'effet de scroll est moins prononcé, mais on peut le garder
        // si des variations futures sont prévues.
        // Actuellement, il n'y a pas de classe 'scrolled' ajoutée/retirée pour changer le style,
        // car le header de cette page est déjà dans son état "scrollé".
        // Si vous voulez un effet de scroll pour changer la couleur ou l'ombre,
        // vous devrez modifier le CSS pour le .header.scrolled et décommenter la logique.
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                // header.classList.add('scrolled'); // Décommenter si vous avez des styles .header.scrolled spécifiques
            } else {
                // header.classList.remove('scrolled'); // Décommenter si vous avez des styles .header.scrolled spécifiques
            }
        });
    }

    // --- Sidebar (Mobile Menu) Toggle (Unified with HomePage) ---
    const sidebar = document.getElementById('sidebar');
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const overlay = document.getElementById('overlay'); // Utilisez l'ID pour l'overlay

    if (menuToggleBtn && sidebar && sidebarCloseBtn && overlay) {
        menuToggleBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        sidebarCloseBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            // Ferme toutes les modales/sidebars ouvertes via l'overlay
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (equipmentModalOverlay) equipmentModalOverlay.classList.remove('active'); // Ajouté pour la modale d'équipement
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Rétablit le scroll après fermeture de tout
        });
    }

    // --- Chat Assistant Modal Toggle (Unified with HomePage) ---
    const formOpenBtn = document.getElementById('form-open');
    const formOpenSidebarBtn = document.getElementById('form-open-sidebar'); // Button in sidebar
    const chatContainer = document.getElementById('chat-container');
    const chatCloseBtn = document.getElementById('chat-close-btn');
    const chatInput = document.getElementById('chat_input');
    const sendChatBtn = document.getElementById('send_chat_btn');
    const chatMessages = document.getElementById('chat-messages');

    function toggleChatModal() {
        if (chatContainer) {
            chatContainer.classList.toggle('active');
            overlay.classList.toggle('active');
            if (chatContainer.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                // Seulement si aucune autre modale/sidebar n'est ouverte
                if (!sidebar.classList.contains('active') &&
                    (!contactSidebar || !contactSidebar.classList.contains('active')) &&
                    (!loginModalOverlay || !loginModalOverlay.classList.contains('active')) &&
                    (!equipmentModalOverlay || !equipmentModalOverlay.classList.contains('active'))) {
                    document.body.style.overflow = '';
                }
            }
        }
    }

    if (formOpenBtn) {
        formOpenBtn.addEventListener('click', toggleChatModal);
    }
    if (formOpenSidebarBtn) {
        formOpenSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            if (sidebar) sidebar.classList.remove('active'); // Close main sidebar
            toggleChatModal(); // Open chat modal
        });
    }
    if (chatCloseBtn) {
        chatCloseBtn.addEventListener('click', toggleChatModal);
    }

    // Basic chat functionality (can be expanded with LLM integration)
    if (sendChatBtn && chatInput && chatMessages) {
        sendChatBtn.addEventListener('click', () => {
            const messageText = chatInput.value.trim();
            if (messageText !== '') {
                const userMessageDiv = document.createElement('div');
                userMessageDiv.classList.add('message', 'user-message');
                userMessageDiv.textContent = messageText;
                chatMessages.appendChild(userMessageDiv);
                chatInput.value = ''; // Clear input
                chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom

                // Simulate bot response (replace with actual LLM call later)
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

    // --- Contact Sidebar Toggle (Unified with HomePage) ---
    const contactOpenBtn = document.getElementById('contact_open_btn');
    const contactOpenSidebarBtn = document.getElementById('contact-open-sidebar-btn'); // Button in sidebar
    const contactSidebar = document.getElementById('contactsidebar');
    const contactSidebarCloseBtn = document.getElementById('contactsidebar_close_btn');

    function toggleContactSidebar() {
        if (contactSidebar) {
            contactSidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            if (contactSidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                // Seulement si aucune autre modale/sidebar n'est ouverte
                if (!sidebar.classList.contains('active') &&
                    (!chatContainer || !chatContainer.classList.contains('active')) &&
                    (!loginModalOverlay || !loginModalOverlay.classList.contains('active')) &&
                    (!equipmentModalOverlay || !equipmentModalOverlay.classList.contains('active'))) {
                    document.body.style.overflow = '';
                }
            }
        }
    }

    if (contactOpenBtn) {
        contactOpenBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            toggleContactSidebar();
        });
    }
    if (contactOpenSidebarBtn) {
        contactOpenSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            if (sidebar) sidebar.classList.remove('active'); // Close main sidebar
            toggleContactSidebar(); // Open contact sidebar
        });
    }
    if (contactSidebarCloseBtn) {
        contactSidebarCloseBtn.addEventListener('click', toggleContactSidebar);
    }

    // --- Login/Registration Modal Toggle ---
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const loginModal = document.getElementById('login-modal');
    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    const openLoginModalTriggers = document.querySelectorAll('.open-login-modal-trigger'); 

    function openLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
            // Ferme les autres modales/sidebars si elles sont ouvertes
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (equipmentModalOverlay) equipmentModalOverlay.classList.remove('active');
            overlay.classList.add('active'); // S'assure que l'overlay est actif
        }
    }

    function closeLoginModal() {
        if (loginModalOverlay) {
            loginModalOverlay.classList.remove('active');
            // Seulement si aucune autre modale/sidebar n'est ouverte, rétablir le scroll
            if (!sidebar.classList.contains('active') &&
                (!contactSidebar || !contactSidebar.classList.contains('active')) &&
                (!chatContainer || !chatContainer.classList.contains('active')) &&
                (!loginModalOverlay || !loginModalOverlay.classList.contains('active'))) {
                document.body.style.overflow = '';
                overlay.classList.remove('active'); // Retire l'overlay seulement si aucune autre modale n'est active
            }
        }
    }

    openLoginModalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            openLoginModal();
        });
    });

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

    // --- Equipment Modal Toggle ---
    const equipmentModalOverlay = document.getElementById('Equipment-modal-overlay');
    const equipmentModalCloseBtn = document.getElementById('Equipment-modal-close');
    const showAllEquipmentBtn = document.getElementById('show-all-Equipment-btn');

    function openEquipmentModal() {
        if (equipmentModalOverlay) {
            equipmentModalOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
            // Ferme les autres modales/sidebars si elles sont ouvertes
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            overlay.classList.add('active'); // S'assure que l'overlay est actif
        }
    }

    function closeEquipmentModal() {
        if (equipmentModalOverlay) {
            equipmentModalOverlay.classList.remove('active');
            // Seulement si aucune autre modale/sidebar n'est ouverte, rétablir le scroll
            if (!sidebar.classList.contains('active') &&
                (!contactSidebar || !contactSidebar.classList.contains('active')) &&
                (!chatContainer || !chatContainer.classList.contains('active')) &&
                (!loginModalOverlay || !loginModalOverlay.classList.contains('active'))) {
                document.body.style.overflow = '';
                overlay.classList.remove('active'); // Retire l'overlay seulement si aucune autre modale n'est active
            }
        }
    }

    if (showAllEquipmentBtn) {
        showAllEquipmentBtn.addEventListener('click', openEquipmentModal);
    }

    if (equipmentModalCloseBtn) {
        equipmentModalCloseBtn.addEventListener('click', closeEquipmentModal);
    }

    if (equipmentModalOverlay) {
        equipmentModalOverlay.addEventListener('click', (e) => {
            if (e.target === equipmentModalOverlay) {
                closeEquipmentModal();
            }
        });
    }

    // --- Show All Reviews Button (Basic functionality) ---
    const showAllReviewsBtn = document.getElementById('show-all-reviews-btn');
    if (showAllReviewsBtn) {
        showAllReviewsBtn.addEventListener('click', () => {
            console.log('Afficher tous les avis cliqué. Implémenter une modale ou une redirection ici.');
        });
    }

    // --- Read More Description (Basic functionality) ---
    const readMoreBtn = document.querySelector('.description-section .read-more');
    if (readMoreBtn) {
        readMoreBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('En savoir plus cliqué. Implémenter l\'extension de la description ici.');
            const descriptionParagraph = readMoreBtn.previousElementSibling;
            if (descriptionParagraph) {
                if (descriptionParagraph.classList.contains('expanded')) {
                    descriptionParagraph.classList.remove('expanded');
                    readMoreBtn.innerHTML = 'En savoir plus <i class="fas fa-chevron-right"></i>';
                } else {
                    descriptionParagraph.classList.add('expanded');
                    readMoreBtn.innerHTML = 'Moins <i class="fas fa-chevron-up"></i>';
                }
            }
        });
    }
});
