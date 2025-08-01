// Il est recommandé de placer ce script dans resources/js/apartment-details.js
// et de l'importer dans resources/js/app.js ou directement dans apartment-details.blade.php

// Sélection des éléments DOM pour la modale des équipements
const showAllAmenitiesBtn = document.getElementById("show-all-amenities-btn");
const amenitiesModalOverlay = document.getElementById("amenities-modal-overlay");
const amenitiesModalCloseBtn = document.getElementById("amenities-modal-close");

// Fonction pour ouvrir la modale des équipements
if (showAllAmenitiesBtn) {
    showAllAmenitiesBtn.addEventListener("click", () => {
        amenitiesModalOverlay.classList.add("active");
        document.body.style.overflow = "hidden"; // Empêche le défilement du corps de la page
    });
}

// Fonction pour fermer la modale des équipements
if (amenitiesModalCloseBtn) {
    amenitiesModalCloseBtn.addEventListener("click", () => {
        amenitiesModalOverlay.classList.remove("active");
        document.body.style.overflow = ""; // Rétablit le défilement du corps de la page
    });
}

// Fermer la modale en cliquant sur l'overlay (en dehors du contenu)
if (amenitiesModalOverlay) {
    amenitiesModalOverlay.addEventListener("click", (e) => {
        if (e.target === amenitiesModalOverlay) {
            amenitiesModalOverlay.classList.remove("active");
            document.body.style.overflow = "";
        }
    });
}

// Optionnel: Fermer la modale avec la touche Échap
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && amenitiesModalOverlay.classList.contains("active")) {
        amenitiesModalOverlay.classList.remove("active");
        document.body.style.overflow = "";
    }
});
