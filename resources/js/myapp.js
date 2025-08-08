


document.addEventListener('DOMContentLoaded', function() {


    // Récupération des éléments du DOM
    const loginModalOverlay = document.getElementById('login-modal-overlay');
    const loginModalCloseBtn = document.getElementById('login-modal-close-btn');

    const loginTabBtn = document.getElementById('login-tab-btn');
    const registerTabBtn = document.getElementById('register-tab-btn');

    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

 

    // --- Gestion de la connexion AJAX ---
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Empêche la soumission par défaut du formulaire

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json' // Demande une réponse JSON
            },
            body: formData
        })
        .then(response => response.json().then(data => {
            if (!response.ok) {
                // Si la réponse n'est pas OK (statut 4xx, 5xx)
                // Affichez les erreurs de validation
                displayErrors(data.errors || { general: [data.message || 'Identifiants incorrects.'] }, loginForm);
                throw new Error('Erreur de connexion');
            }
            return data;
        }))
        .then(data => {
            // Connexion réussie
            console.log('Connexion réussie', data);
            loginModalOverlay.style.display = 'none';
            window.location.reload(); // Recharge la page pour mettre à jour l'état
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });

    // --- Gestion de l'inscription AJAX ---
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
            return data;
        }))
        .then(data => {
            // Inscription réussie
            console.log('Inscription réussie', data);
            loginModalOverlay.style.display = 'none';
            window.location.reload();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });

    // --- Fonction utilitaire pour afficher les erreurs ---
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


    
});