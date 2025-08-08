document.addEventListener('DOMContentLoaded', () => {
    // --- Overlay (Assurez-vous que l'overlay est défini dans votre HTML) ---
    const overlay = document.getElementById('overlay');

    // --- Références à toutes les modales/sidebars pour la gestion de l'overlay ---
    const sidebar = document.getElementById('sidebar');
    const contactSidebar = document.getElementById('contactsidebar');
    const chatContainer = document.getElementById('chat-container');
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const equipmentModalOverlay = document.getElementById('Equipment-modal-overlay');
    const reviewsModalOverlay = document.getElementById('reviews-modal-overlay');

    // Tableau de toutes les modales/sidebars pour une gestion simplifiée
    const allModalsAndSidebars = [
        sidebar,
        contactSidebar,
        chatContainer,
        loginModalOverlay,
        equipmentModalOverlay,
        reviewsModalOverlay
    ].filter(el => el !== null); // Filtre les éléments non trouvés dans le DOM


    // Fonction utilitaire pour vérifier si une modale/sidebar est active
    function isAnyModalOrSidebarActive() {
        return allModalsAndSidebars.some(el => el.classList.contains('active'));
    }

    // Fonction pour gérer le défilement du corps de la page et l'opacité
    function toggleBodyScroll(disableScroll) {
        if (disableScroll) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
        } else {
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
        }
    }

    // --- NOUVELLE FONCTION : Ferme toutes les modales et sidebars ---
    function closeAll() {
        allModalsAndSidebars.forEach(el => {
            el.classList.remove('active');
        });
        if (overlay) {
            overlay.classList.remove('active');
        }
        toggleBodyScroll(false);
    }
    
    // --- NOUVELLE FONCTION : Gère le clic sur l'overlay ---
    if (overlay) {
        overlay.addEventListener('click', closeAll);
    }

    // --- HEADER SCROLL EFFECT --- (Pas de changement)
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

    // --- Sidebar (Mobile Menu) Toggle --- (Légèrement modifié)
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');

    if (menuToggleBtn && sidebar && sidebarCloseBtn && overlay) {
        menuToggleBtn.addEventListener('click', () => {
            closeAll(); // Ferme tout avant d'ouvrir un nouvel élément
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        });

        sidebarCloseBtn.addEventListener('click', closeAll);
    }

    // --- Contact Sidebar Toggle --- (Légèrement modifié)
    const contactOpenBtn = document.getElementById('contact_open_btn');
    const contactOpenSidebarBtn = document.getElementById('contact-open-sidebar-btn');
    const contactSidebarCloseBtn = document.getElementById('contactsidebar_close_btn');

    function toggleContactSidebar() {
        if (contactSidebar) {
            closeAll(); // Ferme tout avant d'ouvrir un nouvel élément
            contactSidebar.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
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
            toggleContactSidebar();
        });
    }
    if (contactSidebarCloseBtn) {
        contactSidebarCloseBtn.addEventListener('click', closeAll);
    }
    
    // --- Login/Registration Modal Toggle --- (Modifié pour utiliser closeAll)
    const openLoginModalBtn = document.getElementById('open-login-modal');
    const openLoginModalTriggers = document.querySelectorAll('.open-login-modal-trigger');
    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

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

    function openLoginModal() {
        if (loginModalOverlay && overlay) {
            closeAll(); // Ferme tout avant d'ouvrir
            loginModalOverlay.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
            showLoginForm(); // Affiche toujours le formulaire de connexion par défaut
        }
    }

    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    if (loginModalCloseBtn) {
        loginModalCloseBtn.addEventListener('click', closeAll);
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

    if (loginModalOverlay) {
        loginModalOverlay.addEventListener('click', (e) => {
            if (e.target === loginModalOverlay) {
                closeAll();
            }
        });
    }

    // --- Equipment Modal Toggle --- (Modifié pour utiliser closeAll)
    const showAllEquipmentBtn = document.getElementById('show-all-Equipment-btn');
    const equipmentModalCloseBtn = document.getElementById('Equipment-modal-close');

    function toggleEquipmentModal() {
        if (equipmentModalOverlay) {
            closeAll(); // Ferme tout avant d'ouvrir
            equipmentModalOverlay.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        }
    }

    if (showAllEquipmentBtn) {
        showAllEquipmentBtn.addEventListener('click', toggleEquipmentModal);
    }
    if (equipmentModalCloseBtn) {
        equipmentModalCloseBtn.addEventListener('click', closeAll);
    }
    if (equipmentModalOverlay) {
        equipmentModalOverlay.addEventListener('click', (e) => {
            if (e.target === equipmentModalOverlay) {
                closeAll();
            }
        });
    }

    // --- Reviews Modal Toggle (Placeholder, assuming similar structure) ---
    const showAllReviewsBtn = document.getElementById('show-all-reviews-btn');
    const reviewsModalCloseBtn = document.getElementById('reviews-modal-close');

    if (showAllReviewsBtn && reviewsModalOverlay) {
        showAllReviewsBtn.addEventListener('click', () => {
            closeAll(); // Ferme tout avant d'ouvrir
            reviewsModalOverlay.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        });
    }
    if (reviewsModalCloseBtn && reviewsModalOverlay) {
        reviewsModalCloseBtn.addEventListener('click', closeAll);
    }
    if (reviewsModalOverlay) {
        reviewsModalOverlay.addEventListener('click', (e) => {
            if (e.target === reviewsModalOverlay) {
                closeAll();
            }
        });
    }

    // --- Image Gallery Functionality ---
    const mainImage = document.querySelector('.apartment-gallery .main-image img');
    const thumbnailImages = document.querySelectorAll('.apartment-gallery .thumbnail-grid img');

    if (mainImage && thumbnailImages.length > 0) {
        thumbnailImages.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                // Swap the src and alt attributes
                const tempSrc = mainImage.src;
                const tempAlt = mainImage.alt;

                mainImage.src = thumbnail.src;
                mainImage.alt = thumbnail.alt;

                thumbnail.src = tempSrc;
                thumbnail.alt = tempAlt;
            });
        });
    }

    // --- Price Calculation Logic for Booking Card ---
    const checkInDateInput = document.getElementById('check_in_date');
    const checkOutDateInput = document.getElementById('check_out_date');
    const numGuestsSelect = document.getElementById('num_guests');
    const pricePerNightSpan = document.querySelector('.booking-header .price');
    const totalPriceElement = document.querySelector('.price-breakdown .total-price span:last-child');
    const nightsPriceBreakdown = document.querySelector('.price-breakdown p:first-child span:last-child');
    const nightsTextBreakdown = document.querySelector('.price-breakdown p:first-child span:first-child');
    const serviceFeeSpan = document.querySelector('.price-breakdown p:nth-child(2) span:last-child');

    // Get the base price from the HTML (assuming it's available or from a data attribute)
    // For now, let's parse it from the initial display. In a real app, you might have it in a hidden input.
    const initialPriceText = pricePerNightSpan ? pricePerNightSpan.textContent : '0 FCFA';
    const basePricePerNight = parseFloat(initialPriceText.replace(/\sFCFA/g, '').replace(/,/g, '.')); // Handle spaces and commas

    const serviceFee = 10000; // Fixed service fee for now

    // Set min date for check-in to today
    const today = new Date();
    today.setDate(today.getDate()); // Set to today
    const todayISO = today.toISOString().split('T')[0];
    if (checkInDateInput) {
        checkInDateInput.min = todayISO;
    }

    function calculateTotalPrice() {
        const checkInDate = checkInDateInput ? new Date(checkInDateInput.value) : null;
        const checkOutDate = checkOutDateInput ? new Date(checkOutDateInput.value) : null;

        if (!checkInDate || !checkOutDate || checkOutDate <= checkInDate) {
            // Reset if dates are invalid or not selected
            nightsTextBreakdown.textContent = `0 FCFA x 0 nuits`;
            nightsPriceBreakdown.textContent = '0 FCFA';
            serviceFeeSpan.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            totalPriceElement.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            return;
        }

        const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
        const numNights = Math.ceil(timeDiff / (1000 * 3600 * 24)); // Calculate nights

        if (numNights <= 0) {
            nightsTextBreakdown.textContent = `0 FCFA x 0 nuits`;
            nightsPriceBreakdown.textContent = '0 FCFA';
            serviceFeeSpan.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
            totalPriceElement.textContent = `${serviceFee.toLocaleString('fr-FR')} FCFA`;
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
    }

    // Add event listeners for date changes and initial calculation
    if (checkInDateInput) {
        checkInDateInput.addEventListener('change', calculateTotalPrice);
    }
    if (checkOutDateInput) {
        checkOutDateInput.addEventListener('change', calculateTotalPrice);
    }
    // No need for numGuestsSelect to trigger price calculation if price is per night per room, not per person.
    // If price changes per person, you'd integrate that logic here.

    // Initial calculation on page load
    calculateTotalPrice();

    // Ensure check-out date is always after check-in date
    if (checkInDateInput && checkOutDateInput) {
        checkInDateInput.addEventListener('change', () => {
            if (checkOutDateInput.value && checkOutDateInput.value < checkInDateInput.value) {
                checkOutDateInput.value = checkInDateInput.value;
            }
            checkOutDateInput.min = checkInDateInput.value;
            calculateTotalPrice();
        });
        checkOutDateInput.addEventListener('change', calculateTotalPrice);
    }
});
