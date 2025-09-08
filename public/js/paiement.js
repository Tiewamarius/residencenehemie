document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector(".submit-payment-btn");
    const radios = document.querySelectorAll("input[name='payment_method']");
    const allDetails = document.querySelectorAll(".payment-details");

    const detailsMap = {
        "payment-mm": "mobile-money-details",
        "payment-wave": "wave-details",
        "payment-espece": "espece-details", // on garde uniquement ça
    };

    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            // Fermer tous les blocs
            allDetails.forEach(d => d.classList.remove("expanded"));

            // Ouvrir uniquement le bloc choisi
            const targetId = detailsMap[radio.id];
            if (targetId) {
                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.add("expanded");
                }
            }

            // Si c'est le paiement en espèce et qu'il est bloqué
            if (radio.id === "payment-espece" && radio.disabled) {
                const warning = document.getElementById("warning-message");
                if (warning) {
                    warning.style.display = "block"; // le rendre visible
                }
                btn.disabled = true; // on empêche la validation
                return;
            }

            // Activer bouton si choix valide
            btn.disabled = false;
        });
    });

    // Masquer le warning par défaut
    const warning = document.getElementById("warning-message");
    if (warning) {
        warning.style.display = "none"; // masqué au chargement
    }
});
