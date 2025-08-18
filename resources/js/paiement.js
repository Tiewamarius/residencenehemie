// Attendre que le contenu de la page soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function() {

    // Sélectionner les éléments du DOM
    const paymentForm = document.getElementById('payment-form');
    const paymentOptions = paymentForm.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.querySelector('.card-details');
    const cardInputs = cardDetails.querySelectorAll('input, select');
    const submitButton = document.querySelector('.submit-payment-btn');

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
            if (this.value === 'carte') {
                // Activer les champs si 'carte' est sélectionné
                toggleCardInputs(true);
            } else {
                // Désactiver les champs si un autre mode est sélectionné
                toggleCardInputs(false);
            }
        });
    });

    // Activer les champs si la page est chargée avec l'option 'carte' déjà cochée
    if (document.querySelector('input[name="payment_method"][value="carte"]').checked) {
        toggleCardInputs(true);
    }

    // Le formulaire est déjà géré par Laravel, mais ce script est prêt à être étendu
    // pour des validations front-end ou des interactions via une API.
});
