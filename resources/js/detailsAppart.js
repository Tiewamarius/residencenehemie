document.addEventListener('DOMContentLoaded', () => {
    // --- Overlay (Assurez-vous que l'overlay est défini dans votre HTML) ---
    const overlay = document.getElementById('overlay');

    // --- Références à toutes les modales/sidebars pour la gestion de l'overlay ---
    // const sidebar = document.getElementById('sidebar');
    // const contactSidebar = document.getElementById('contactsidebar');
    const chatContainer = document.getElementById('chat-container');
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const equipmentModalOverlay = document.getElementById('Equipment-modal-overlay');
    const reviewsModalOverlay = document.getElementById('reviews-modal-overlay'); // Assuming you might add this for reviews


    
    // Fonction utilitaire pour vérifier si une modale/sidebar est active
    function isAnyModalOrSidebarActive() {
        return (sidebar && sidebar.classList.contains('active')) ||
               (contactSidebar && contactSidebar.classList.contains('active')) ||
               (chatContainer && chatContainer.classList.contains('active')) ||
               (loginModalOverlay && loginModalOverlay.classList.contains('active')) ||
               (equipmentModalOverlay && equipmentModalOverlay.classList.contains('active')) ||
               (reviewsModalOverlay && reviewsModalOverlay.classList.contains('active'));
    }

    // Fonction pour gérer le défilement du corps de la page et l'opacité
    function toggleBodyScroll(disableScroll) {
        if (disableScroll) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open'); // Add class when a modal/sidebar is open
        } else {
            // Restore scroll and remove class only if NO other modal/sidebar is active
            if (!isAnyModalOrSidebarActive()) {
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open'); // Remove class when all are closed
            }
        }
    }

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

    // --- Sidebar (Mobile Menu) Toggle ---
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const sidebarCloseBtn = document.getElementById('sidebar-close-btn');

    if (menuToggleBtn && sidebar && sidebarCloseBtn && overlay) {
        menuToggleBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
        });

        sidebarCloseBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            toggleBodyScroll(false);
            // Overlay will be managed by its own listener if no other modal is open
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
            // Close other modals/sidebars if open
            if (sidebar) sidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (equipmentModalOverlay) equipmentModalOverlay.classList.remove('active');
            if (reviewsModalOverlay) reviewsModalOverlay.classList.remove('active');
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
    const openLoginModalBtn = document.getElementById('open-login-modal'); // Header Favorites link for guests
    const openLoginModalTriggers = document.querySelectorAll('.open-login-modal-trigger'); // "Enregistrer" link on apartment page

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
            loginModalOverlay.classList.add('active');
            overlay.classList.add('active');
            toggleBodyScroll(true);
            // Close other modals/sidebars if open
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (equipmentModalOverlay) equipmentModalOverlay.classList.remove('active');
            if (reviewsModalOverlay) reviewsModalOverlay.classList.remove('active');

            showLoginForm(); // Always show login form by default
        }
    }

    function closeLoginModal() {
        if (loginModalOverlay && overlay) {
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

    // --- Equipment Modal Toggle ---
    const showAllEquipmentBtn = document.getElementById('show-all-Equipment-btn');
    const equipmentModalCloseBtn = document.getElementById('Equipment-modal-close');

    function toggleEquipmentModal() {
        if (equipmentModalOverlay) {
            equipmentModalOverlay.classList.toggle('active');
            overlay.classList.toggle('active');
            toggleBodyScroll(equipmentModalOverlay.classList.contains('active'));
            // Close other modals/sidebars if open
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (reviewsModalOverlay) reviewsModalOverlay.classList.remove('active');
        }
    }

    if (showAllEquipmentBtn) {
        showAllEquipmentBtn.addEventListener('click', toggleEquipmentModal);
    }
    if (equipmentModalCloseBtn) {
        equipmentModalCloseBtn.addEventListener('click', toggleEquipmentModal);
    }
    if (equipmentModalOverlay) {
        equipmentModalOverlay.addEventListener('click', (e) => {
            if (e.target === equipmentModalOverlay) {
                toggleEquipmentModal();
            }
        });
    }

    // --- Reviews Modal Toggle (Placeholder, assuming similar structure) ---
    const showAllReviewsBtn = document.getElementById('show-all-reviews-btn');
    // const reviewsModalCloseBtn = document.getElementById('reviews-modal-close'); // You'd need this ID in HTML

    // if (showAllReviewsBtn && reviewsModalOverlay) {
    //     showAllReviewsBtn.addEventListener('click', () => {
    //         reviewsModalOverlay.classList.add('active');
    //         overlay.classList.add('active');
    //         toggleBodyScroll(true);
    //     });
    // }
    // if (reviewsModalCloseBtn && reviewsModalOverlay) {
    //     reviewsModalCloseBtn.addEventListener('click', () => {
    //         reviewsModalOverlay.classList.remove('active');
    //         overlay.classList.remove('active');
    //         toggleBodyScroll(false);
    //     });
    // }
    // if (reviewsModalOverlay) {
    //     reviewsModalOverlay.addEventListener('click', (e) => {
    //         if (e.target === reviewsModalOverlay) {
    //             reviewsModalOverlay.classList.remove('active');
    //             overlay.classList.remove('active');
    //             toggleBodyScroll(false);
    //         }
    //     });
    // }

    // --- Overlay global click listener (to close all) ---
    if (overlay) {
        overlay.addEventListener('click', () => {
            if (sidebar) sidebar.classList.remove('active');
            if (contactSidebar) contactSidebar.classList.remove('active');
            if (chatContainer) chatContainer.classList.remove('active');
            if (loginModalOverlay) loginModalOverlay.classList.remove('active');
            if (equipmentModalOverlay) equipmentModalOverlay.classList.remove('active');
            if (reviewsModalOverlay) reviewsModalOverlay.classList.remove('active');
            
            overlay.classList.remove('active');
            toggleBodyScroll(false); // This will now correctly remove 'modal-open' if nothing else is active
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
