document.addEventListener('DOMContentLoaded', function() {
    // Récupération des éléments du DOM
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');
    const openLoginBtn = document.getElementById('open-login-btn'); // Supposé être le bouton pour ouvrir le modal

    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');

    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


    // --- Fonctions utilitaires ---
    function openModal() {
        loginModalOverlay.style.display = 'flex';
    }

    function closeModal() {
        loginModalOverlay.style.display = 'none';
        // Supprime les erreurs lors de la fermeture
        loginForm.querySelectorAll('.error-message').forEach(el => el.remove());
        registerForm.querySelectorAll('.error-message').forEach(el => el.remove());
    }

    function switchTab(tabToActivate, sectionToActivate) {
        // Gérer les onglets
        loginTabBtn.classList.remove('active-tab');
        registerTabBtn.classList.remove('active-tab');
        tabToActivate.classList.add('active-tab');

        // Gérer les sections
        loginSection.style.display = 'none';
        registerSection.style.display = 'none';
        sectionToActivate.style.display = 'block';
    }

    function displayErrors(errors, form) {
        // Supprime tous les messages d'erreur existants
        form.querySelectorAll('.error-message').forEach(el => el.remove());

        // Pour chaque champ en erreur, ajoutez un message
        for (const field in errors) {
            const messages = errors[field];
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.style.color = 'red';
                errorDiv.style.fontSize = '0.8em';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = messages[0];
                input.parentNode.appendChild(errorDiv);
            }
        }
    }


    // --- Gestion des événements du modal et des onglets ---
    // Supposons qu'il y ait un bouton pour ouvrir le modal
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', openModal);
    }
    
    if (loginModalCloseBtn) {
        loginModalCloseBtn.addEventListener('click', closeModal);
    }

    if (loginTabBtn) {
        loginTabBtn.addEventListener('click', () => switchTab(loginTabBtn, loginSection));
    }

    if (registerTabBtn) {
        registerTabBtn.addEventListener('click', () => switchTab(registerTabBtn, registerSection));
    }


    // --- Soumission des formulaires (Logique AJAX) ---
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => {
                if (!response.ok) {
                    // Si la réponse n'est pas OK (statut 4xx, 5xx)
                    const errors = data.errors || { general: [data.message || 'Identifiants incorrects.'] };
                    displayErrors(errors, loginForm);
                    throw new Error('Erreur de connexion');
                }
                // Connexion réussie
                console.log('Connexion réussie', data);
                closeModal();
                window.location.reload();
            }))
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json().then(data => {
                if (!response.ok) {
                    displayErrors(data.errors, registerForm);
                    throw new Error('Erreur d\'inscription');
                }
                // Inscription réussie
                console.log('Inscription réussie', data);
                closeModal();
                window.location.reload();
            }))
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    }
});