// Attendre que le contenu de la page soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function() {
    
    // Sélectionner les éléments du DOM et vérifier leur existence
    const paymentForm = document.getElementById('payment-form');
    // Si le formulaire de paiement n'existe pas, on arrête le script.
    if (!paymentForm) {
        console.error("Erreur : Le formulaire avec l'ID 'payment-form' est introuvable.");
        return; 
    }

    const paymentOptions = paymentForm.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.querySelector('.card-details');
    const submitButton = document.querySelector('.submit-payment-btn');

    // Les champs de la carte ne sont pertinents que si le bloc 'cardDetails' existe.
    let cardInputs = [];
    if (cardDetails) {
        cardInputs = cardDetails.querySelectorAll('input, select');
    }

    /**
     * Active ou désactive les champs de la carte de crédit.
     * @param {boolean} isEnabled - Vrai pour activer, Faux pour désactiver.
     */
    function toggleCardInputs(isEnabled) {
        cardInputs.forEach(input => {
            input.disabled = !isEnabled;
        });
    }

    // Gérer le changement de mode de paiement
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            // S'assurer que les détails de la carte existent avant de les manipuler
            if (cardDetails) {
                if (this.value === 'carte') {
                    // Activer les champs si 'carte' est sélectionné
                    toggleCardInputs(true);
                } else {
                    // Désactiver les champs si un autre mode est sélectionné
                    toggleCardInputs(false);
                }
            }
        });
    });

    // S'assurer que le bloc 'cardDetails' existe avant de le manipuler
    if (cardDetails) {
        // Activer les champs si la page est chargée avec l'option 'carte' déjà cochée
        const cardOption = document.querySelector('input[name="payment_method"][value="carte"]');
        if (cardOption && cardOption.checked) {
            toggleCardInputs(true);
        }
    }

    // Le formulaire est déjà géré par Laravel, mais ce script est prêt à être étendu
    // pour des validations front-end ou des interactions via une API.
});
