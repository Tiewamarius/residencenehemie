{{-- resources/views/emails/notification.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            padding: 20px;
            text-align: center;
            background: #2563eb;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .content {
            padding: 20px;
            color: #374151;
        }

        .content h2 {
            margin-top: 0;
            font-size: 18px;
            color: #111827;
        }

        .info {
            margin: 15px 0;
        }

        .info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .highlight {
            font-weight: bold;
            color: #2563eb;
        }

        .footer {
            background: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if($type === 'new')
            <h1>📩 Nouvelle réservation</h1>
            @elseif($type === 'update')
            <h1>🔄 Réservation modifiée</h1>
            @elseif($type === 'cancel')
            <h1>❌ Réservation annulée</h1>
            @else
            <h1>ℹ️ Notification réservation</h1>
            @endif
        </div>

        <div class="content">
            <h2>Détails de la réservation</h2>
            <div class="info">
                <p><span class="highlight">Client :</span> {{ $booking->user->name }} ({{ $booking->user->email }})</p>
                <p><span class="highlight">Résidence :</span> {{ $booking->residence->nom }}</p>
                <p><span class="highlight">Type :</span> {{ $booking->type->nom }}</p>
                <p><span class="highlight">Arrivée :</span> {{ \Carbon\Carbon::parse($booking->date_arrivee)->format('d/m/Y') }}</p>
                <p><span class="highlight">Départ :</span> {{ \Carbon\Carbon::parse($booking->date_depart)->format('d/m/Y') }}</p>
                <p><span class="highlight">Prix total :</span> {{ number_format($booking->total_price, 0, ',', ' ') }} CFA</p>
                <p><span class="highlight">Statut :</span> {{ $booking->statut }}</p>
            </div>

            @if($type === 'new')
            <p>Une nouvelle réservation vient d’être effectuée. 🎉</p>
            @elseif($type === 'update')
            <p>La réservation a été mise à jour par le client.</p>
            @elseif($type === 'cancel')
            <p>La réservation a été annulée.</p>
            @endif
        </div>

        <div class="footer">
            <p>💡 Ceci est un message automatique, merci de ne pas répondre directement à cet email.</p>
        </div>
    </div>
</body>

</html>