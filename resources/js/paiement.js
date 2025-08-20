// Attendre que le contenu de la page soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function() {

    // Sélectionner les éléments du DOM
    const paymentForm = document.getElementById('payment-form');
    // Vérifier si le formulaire de paiement existe pour éviter les erreurs
    if (!paymentForm) {
        console.error("Erreur : Le formulaire avec l'ID 'payment-form' est introuvable.");
        return;
    }

    const paymentOptions = paymentForm.querySelectorAll('input[name="payment_method"]');
    const paymentDetailsSections = document.querySelectorAll('.payment-details');

    /**
     * Gère l'affichage des champs de saisie en fonction du mode de paiement sélectionné.
     */
    function updatePaymentFields() {
        const selectedValue = document.querySelector('input[name="payment_method"]:checked').value;

        // Cacher tous les conteneurs de détails de paiement
        paymentDetailsSections.forEach(section => {
            section.classList.add('hidden');
            // Désactiver tous les champs de saisie pour éviter qu'ils soient envoyés si cachés
            section.querySelectorAll('input, select').forEach(input => {
                input.disabled = true;
            });
        });

        // Trouver le conteneur de détails correspondant à l'option sélectionnée
        const selectedDetailsContainer = document.querySelector(`.payment-details.${selectedValue}-details`);
        
        // Afficher le bon conteneur et activer ses champs de saisie
        if (selectedDetailsContainer) {
            selectedDetailsContainer.classList.remove('hidden');
            selectedDetailsContainer.querySelectorAll('input, select').forEach(input => {
                input.disabled = false;
            });
        }
    }

    // Ajouter un écouteur d'événement à chaque bouton radio
    paymentOptions.forEach(option => {
        option.addEventListener('change', updatePaymentFields);
    });

    // Appeler la fonction une fois au chargement de la page pour définir l'état initial
    updatePaymentFields();
});
