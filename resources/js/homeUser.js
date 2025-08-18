
    document.addEventListener('DOMContentLoaded', (event) => {
        // Sélectionne tous les liens de navigation d'onglet
        const navItems = document.querySelectorAll('.profile-nav-item');
        // Sélectionne tous les conteneurs de contenu d'onglet
        const tabContents = document.querySelectorAll('.tab-content');

        // Ajoute un écouteur d'événement de clic à chaque lien de navigation
        navItems.forEach(item => {
            item.addEventListener('click', function(event) {
                // Empêche le comportement par défaut du lien
                event.preventDefault();

                // Récupère l'ID de l'onglet à partir de l'attribut data-tab
                const tabId = this.getAttribute('data-tab');

                // Supprime la classe 'active' de tous les éléments de navigation
                navItems.forEach(nav => nav.classList.remove('active'));

                // Ajoute la classe 'active' à l'élément cliqué
                this.classList.add('active');

                // Cache tous les conteneurs de contenu d'onglet
                tabContents.forEach(content => content.classList.add('hidden'));

                // Affiche le conteneur de contenu d'onglet correspondant
                const activeTabContent = document.getElementById(tabId);
                if (activeTabContent) {
                    activeTabContent.classList.remove('hidden');
                }
            });
        });
    });
