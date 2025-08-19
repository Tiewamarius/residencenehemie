/**
 * Script pour gérer la navigation par onglets sur la page du tableau de bord.
 *
 * Ce script écoute les clics sur les éléments de navigation de la barre latérale.
 * Lorsqu'un élément est cliqué, il affiche le contenu de l'onglet correspondant
 * et met à jour les classes pour refléter l'onglet actif.
 */
document.addEventListener('DOMContentLoaded', () => {

    // Sélectionne tous les éléments de navigation avec la classe 'profile-nav-item'
    const navItems = document.querySelectorAll('.profile-nav-item');

    // Sélectionne tous les conteneurs de contenu des onglets avec la classe 'tab-content'
    const tabContents = document.querySelectorAll('.tab-content');

    /**
     * Cache tous les onglets de contenu et supprime la classe 'active' de tous les éléments de navigation.
     */
    const resetTabs = () => {
        // Supprime la classe 'active' de tous les éléments de navigation
        navItems.forEach(item => {
            item.classList.remove('active');
        });

        // Cache tous les conteneurs de contenu des onglets
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });
    };

    /**
     * Met en place les écouteurs d'événements pour chaque élément de navigation.
     */
    navItems.forEach(item => {
        item.addEventListener('click', (event) => {
            // Empêche le comportement par défaut du lien (rechargement de la page)
            event.preventDefault();

            // Récupère l'ID de l'onglet cible à partir de l'attribut 'data-tab'
            const targetTabId = item.getAttribute('data-tab');

            // Réinitialise l'état de tous les onglets et éléments de navigation
            resetTabs();

            // Ajoute la classe 'active' à l'élément de navigation cliqué
            item.classList.add('active');

            // Affiche l'onglet de contenu correspondant en retirant la classe 'hidden'
            const targetTab = document.getElementById(targetTabId);
            if (targetTab) {
                targetTab.classList.remove('hidden');
            }
        });
    });

    // Initialisation : au chargement, on cache tout d'abord...
    resetTabs();

    // ...puis on affiche l'onglet par défaut (celui avec la classe 'active' dans le HTML)
    const activeItem = document.querySelector('.profile-nav-item.active');
    if (activeItem) {
        const targetTabId = activeItem.getAttribute('data-tab');
        const targetTab = document.getElementById(targetTabId);
        if (targetTab) {
            targetTab.classList.remove('hidden');
        }
    }
});
