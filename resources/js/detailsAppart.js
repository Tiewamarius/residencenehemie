document.addEventListener('DOMContentLoaded', () => {

    // --- Sélecteurs d'éléments ---
    const overlay = document.getElementById('overlay');
    const sidebar = document.getElementById('sidebar');
    const contactSidebar = document.getElementById('contactsidebar');
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const equipmentModalOverlay = document.getElementById('Equipment-modal-overlay');
    const reviewsModalOverlay = document.getElementById('reviews-modal-overlay');
    const header = document.querySelector('.header');

    // Boutons et liens pour les modales/sidebars
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
    const contactOpenBtns = document.querySelectorAll('#contact_open_btn, #contact-open-sidebar-btn');
    const contactSidebarCloseBtn = document.getElementById('contactsidebar_close_btn');
    const openLoginModalTriggers = document.querySelectorAll('.open-login-modal-trigger, #open-login-modal');
    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    const showAllEquipmentBtn = document.getElementById('show-all-Equipment-btn');
    const equipmentModalCloseBtn = document.getElementById('Equipment-modal-close');
    const showAllReviewsBtn = document.getElementById('show-all-reviews-btn');
    const reviewsModalCloseBtn = document.getElementById('reviews-modal-close');

    // Éléments de la modale de connexion
    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

    // Éléments de la galerie d'images
    const mainImage = document.querySelector('.apartment-gallery .main-image img');
    const thumbnailImages = document.querySelectorAll('.apartment-gallery .thumbnail-grid img');

    // Éléments de la carte de réservation
    const checkInDateInput = document.getElementById('check_in_date');
    const checkOutDateInput = document.getElementById('check_out_date');
    const pricePerNightElement = document.querySelector('.booking-header .price');
    const nightsDisplayBreakdown = document.getElementById('nights-display-breakdown');
    const priceSubtotal = document.getElementById('price-subtotal');
    const priceTotal = document.getElementById('price-total');
    const totalPriceInput = document.getElementById('total-price-input'); // Ajout de la référence
    const reserveButton = document.querySelector('.check-availability-btn');

    // Tableau de toutes les modales/sidebars pour une gestion simplifiée
    const allModalsAndSidebars = [
        sidebar,
        contactSidebar,
        loginModalOverlay,
        equipmentModalOverlay,
        reviewsModalOverlay
    ].filter(el => el !== null);

    // --- Fonctions utilitaires pour la gestion des modales/sidebars ---

    /**
     * Désactive ou active le défilement du corps de la page.
     * @param {boolean} disableScroll - Vrai pour désactiver, Faux pour activer.
     */
    function toggleBodyScroll(disableScroll) {
        if (disableScroll) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
        } else {
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
        }
    }

    /**
     * Ferme toutes les modales et sidebars actives.
     */
    function closeAll() {
        allModalsAndSidebars.forEach(el => el.classList.remove('active'));
        if (overlay) {
            overlay.classList.remove('active');
        }
        toggleBodyScroll(false);
    }

    /**
     * Ouvre une modale ou une sidebar spécifique en fermant les autres.
     * @param {HTMLElement} elementToOpen - L'élément à ouvrir.
     */
    function openModalOrSidebar(elementToOpen) {
        if (!elementToOpen) return;
        
        closeAll();
        elementToOpen.classList.add('active');
        if (overlay) {
            overlay.classList.add('active');
        }
        toggleBodyScroll(true);
    }

    // --- Écouteurs d'événements : GESTION CENTRALISÉE ---

    if (overlay) overlay.addEventListener('click', closeAll);
    
    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 50);
        });
    }
    
    if (menuToggleBtn) menuToggleBtn.addEventListener('click', () => openModalOrSidebar(sidebar));
    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeAll);

    contactOpenBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openModalOrSidebar(contactSidebar);
        });
    });
    if (contactSidebarCloseBtn) contactSidebarCloseBtn.addEventListener('click', closeAll);

    function showLoginForm() {
        if (loginSection && registerSection && loginTabBtn && registerTabBtn) {
            loginSection.classList.add('active');
            registerSection.classList.remove('active');
            loginTabBtn.classList.add('active');
            registerTabBtn.classList.remove('active');
        }
    }

    function showRegisterForm() {
        if (loginSection && registerSection && loginTabBtn && registerTabBtn) {
            registerSection.classList.add('active');
            loginSection.classList.remove('active');
            registerTabBtn.classList.add('active');
            loginTabBtn.classList.remove('active');
        }
    }

    if (loginTabBtn && registerTabBtn) {
        loginTabBtn.addEventListener('click', showLoginForm);
        registerTabBtn.addEventListener('click', showRegisterForm);
    }

    openLoginModalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            openModalOrSidebar(loginModalOverlay);
            showLoginForm();
        });
    });

    if (loginModalCloseBtn) loginModalCloseBtn.addEventListener('click', closeAll);

    if (showAllEquipmentBtn) showAllEquipmentBtn.addEventListener('click', () => openModalOrSidebar(equipmentModalOverlay));
    if (equipmentModalCloseBtn) equipmentModalCloseBtn.addEventListener('click', closeAll);

    if (showAllReviewsBtn) showAllReviewsBtn.addEventListener('click', () => openModalOrSidebar(reviewsModalOverlay));
    if (reviewsModalCloseBtn) reviewsModalCloseBtn.addEventListener('click', closeAll);

    const closableModals = [loginModalOverlay, equipmentModalOverlay, reviewsModalOverlay].filter(el => el !== null);
    closableModals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeAll();
            }
        });
    });

    // --- Fonctionnalité de la galerie d'images ---
    if (mainImage && thumbnailImages.length > 0) {
        thumbnailImages.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                mainImage.src = thumbnail.src;
                mainImage.alt = thumbnail.alt;
            });
        });
    }

    // --- Logique de calcul du prix pour la carte de réservation ---
    function parsePrice(priceText) {
        if (!priceText) return 0;
        return parseInt(priceText.replace(/\s/g, '').replace('FCFA', ''), 10);
    }

    const basePricePerNight = parsePrice(pricePerNightElement ? pricePerNightElement.textContent : '0 FCFA');
    const serviceFee = 10000;

    /**
     * Calcule et affiche le prix total de la réservation.
     */
    function calculateTotalPrice() {
        const checkInDate = checkInDateInput ? new Date(checkInDateInput.value) : null;
        const checkOutDate = checkOutDateInput ? new Date(checkOutDateInput.value) : null;

        let totalAmount = serviceFee;
        let nights = 0;
        let subtotal = 0;

        if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
            const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
            nights = Math.round(timeDiff / (1000 * 3600 * 24));
            
            subtotal = basePricePerNight * nights;
            totalAmount = subtotal + serviceFee;
        }

        if (nightsDisplayBreakdown) nightsDisplayBreakdown.textContent = nights;
        if (priceSubtotal) priceSubtotal.textContent = subtotal.toLocaleString('fr-FR');
        if (priceTotal) priceTotal.textContent = totalAmount.toLocaleString('fr-FR') + ' FCFA';
        if (totalPriceInput) totalPriceInput.value = totalAmount; // CORRECTION MAJEURE: Mise à jour du champ caché
        if (reserveButton) reserveButton.disabled = nights === 0;
    }

    // --- Initialisation de Flatpickr pour les champs de date ---
    if (checkInDateInput && checkOutDateInput) {
        flatpickr(checkInDateInput, {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function(selectedDates, dateStr) {
                // S'assure que la date de départ est toujours après la date d'arrivée
                if (checkOutDateInput.value && dateStr > checkOutDateInput.value) {
                    checkOutDateInput.value = dateStr;
                }
                if (checkOutDateInput._flatpickr) {
                    checkOutDateInput._flatpickr.set('minDate', dateStr);
                }
                calculateTotalPrice();
            }
        });

        flatpickr(checkOutDateInput, {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: checkInDateInput.value || 'today',
            onChange: calculateTotalPrice
        });
    }
    
    // Premier calcul au chargement de la page
    calculateTotalPrice();
});
