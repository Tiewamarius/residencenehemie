<h1>Bonjour {{ $booking->user->name }},</h1>

<p>🎉 Félicitations ! Votre réservation a bien été enregistrée.
    Nous préparons votre séjour avec soin voici tous les détails nécessaires:</p>

<p>Numéro de réservation : <strong>{{ $booking->numero_reservation }}</strong></p>
<p>Date d'arrivée : {{ $booking->date_arrivee }}</p>
<p>Date de départ : {{ $booking->date_depart }}</p>

<!-- Ajoutez plus de détails selon vos besoins -->

<p>Cordialement,</p>
<p>Residence Néhemie votre plateforme de réservation</p>