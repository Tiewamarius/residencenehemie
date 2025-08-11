document.addEventListener('DOMContentLoaded', () => {

    // --- Sélecteurs d'éléments : une seule fois pour tout le script ---
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
    const pricePerNightSpan = document.querySelector('.booking-header .price');
    const nightsTextBreakdown = document.querySelector('.price-breakdown p:first-child span:first-child');
    const nightsPriceBreakdown = document.querySelector('.price-breakdown p:first-child span:last-child');
    const serviceFeeSpan = document.querySelector('.price-breakdown p:nth-child(2) span:last-child');
    const totalPriceElement = document.querySelector('.price-breakdown .total-price span:last-child');
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

    // Événement de clic sur l'overlay pour fermer toutes les modales
    if (overlay) {
        overlay.addEventListener('click', closeAll);
    }

    // Effet de défilement de l'en-tête
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Événements pour le menu latéral (sidebar)
    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', () => openModalOrSidebar(sidebar));
    }
    if (sidebarCloseBtn) {
        sidebarCloseBtn.addEventListener('click', closeAll);
    }

    // Événements pour la barre latérale de contact
    contactOpenBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openModalOrSidebar(contactSidebar);
        });
    });
    if (contactSidebarCloseBtn) {
        contactSidebarCloseBtn.addEventListener('click', closeAll);
    }

    // Événements pour la modale de connexion
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
            showLoginForm(); // Afficher toujours le formulaire de connexion par défaut
        });
    });

    if (loginModalCloseBtn) {
        loginModalCloseBtn.addEventListener('click', closeAll);
    }

    // Événements pour la modale d'équipement
    if (showAllEquipmentBtn) {
        showAllEquipmentBtn.addEventListener('click', () => openModalOrSidebar(equipmentModalOverlay));
    }
    if (equipmentModalCloseBtn) {
        equipmentModalCloseBtn.addEventListener('click', closeAll);
    }

    // Événements pour la modale des avis
    if (showAllReviewsBtn) {
        showAllReviewsBtn.addEventListener('click', () => openModalOrSidebar(reviewsModalOverlay));
    }
    if (reviewsModalCloseBtn) {
        reviewsModalCloseBtn.addEventListener('click', closeAll);
    }

    // Ajout d'écouteurs pour fermer les modales en cliquant sur leur propre arrière-plan
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
                // Change l'image principale pour afficher la vignette cliquée
                mainImage.src = thumbnail.src;
                mainImage.alt = thumbnail.alt;

                // Optionnel : on pourrait aussi mettre en surbrillance la vignette active
                // thumbnailImages.forEach(img => img.classList.remove('active'));
                // thumbnail.classList.add('active');
            });
        });
    }

    // --- Logique de calcul du prix pour la carte de réservation ---
    const initialPriceText = pricePerNightSpan ? pricePerNightSpan.textContent : '0 FCFA';
    const basePricePerNight = parseFloat(initialPriceText.replace(/\sFCFA/g, '').replace(/,/g, '.'));
    const serviceFee = 10000;

    // Définit la date minimale pour l'arrivée à la date d'aujourd'hui
    const today = new Date();
    const todayISO = today.toISOString().split('T')[0];

    /**
     * Calcule et affiche le prix total de la réservation.
     */
    function calculateTotalPrice() {
        const checkInDate = checkInDateInput ? new Date(checkInDateInput.value) : null;
        const checkOutDate = checkOutDateInput ? new Date(checkOutDateInput.value) : null;

        let isValid = checkInDate && checkOutDate && checkOutDate > checkInDate;

        if (!isValid) {
            if (nightsTextBreakdown) nightsTextBreakdown.textContent = `${basePricePerNight.toLocaleString('fr-FR')} FCFA x 0 nuit(s)`;
            if (nightsPriceBreakdown) nightsPriceBreakdown.textContent = '0 FCFA';
            if (serviceFeeSpan) serviceFeeSpan.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            if (totalPriceElement) totalPriceElement.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            if (reserveButton) reserveButton.disabled = true;
            return;
        }

        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const numNights = Math.ceil(timeDiff / (1000 * 3600 * 24));

        if (numNights <= 0) {
            if (nightsTextBreakdown) nightsTextBreakdown.textContent = `${basePricePerNight.toLocaleString('fr-FR')} FCFA x 0 nuit(s)`;
            if (nightsPriceBreakdown) nightsPriceBreakdown.textContent = '0 FCFA';
            if (serviceFeeSpan) serviceFeeSpan.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            if (totalPriceElement) totalPriceElement.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            if (reserveButton) reserveButton.disabled = true;
            return;
        }

        const nightsTotal = basePricePerNight * numNights;
        const totalAmount = nightsTotal + serviceFee;

        if (nightsTextBreakdown) {
            nightsTextBreakdown.textContent = `${basePricePerNight.toLocaleString('fr-FR')} FCFA x ${numNights} nuit${numNights > 1 ? 's' : ''}`;
        }
        if (nightsPriceBreakdown) {
            nightsPriceBreakdown.textContent = `${nightsTotal.toLocaleString('fr-FR')} FCFA`;
        }
        if (serviceFeeSpan) {
            serviceFeeSpan.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
        }
        if (totalPriceElement) {
            totalPriceElement.textContent = `${totalAmount.toLocaleString('fr-FR')} FCFA`;
        }
        if (reserveButton) reserveButton.disabled = false;
    }

    // --- Initialisation de Flatpickr pour les champs de date ---
    if (checkInDateInput && checkOutDateInput) {
        flatpickr(checkInDateInput, {
            locale: 'fr',
            dateFormat: 'Y-m-d',
            minDate: 'today',
            onChange: function(selectedDates, dateStr, instance) {
                // S'assure que la date de départ n'est pas antérieure à la date d'arrivée
                if (checkOutDateInput.value && dateStr > checkOutDateInput.value) {
                    checkOutDateInput.value = dateStr;
                }
                // Met à jour la date minimale de départ
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
