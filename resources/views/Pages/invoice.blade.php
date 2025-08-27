<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Facture - {{ $reservation->numero_reservation }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 40px;
            background: #f9fafb;
            color: #333;
        }

        .invoice-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #1f2937;
        }

        .company {
            text-align: right;
        }

        .details,
        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .details td,
        .summary td {
            padding: 8px;
        }

        .details {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .details th {
            text-align: left;
            background: #f3f4f6;
            padding: 10px;
        }

        .summary {
            border-top: 2px solid #e5e7eb;
        }

        .summary td {
            font-size: 14px;
        }

        .summary .total {
            font-weight: bold;
            font-size: 18px;
            color: #111827;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <!-- HEADER -->
        <div class="header">
            <div>
                <h1>Facture</h1>
                <p>Réservation n° <strong>{{ $reservation->numero_reservation }}</strong></p>
            </div>
            <div class="company">
                <h2>Résidences Néhémie</h2>
                <p>Abidjan, Côte d’Ivoire<br>
                    Tél: +225 07 00 00 00<br>
                    Email: contact@residences-nehemie.com</p>
            </div>
        </div>

        <!-- CLIENT -->
        <h3>Informations du client</h3>
        <table class="details" cellspacing="0" cellpadding="0">
            <tr>
                <th>Nom</th>
                <td>{{ $reservation->user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $reservation->user->email }}</td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td>{{ $reservation->user->phone_number ?? 'Non renseigné' }}</td>
            </tr>
        </table>

        <!-- RÉSERVATION -->
        <h3>Détails de la réservation</h3>
        <table class="details" cellspacing="0" cellpadding="0">
            <tr>
                <th>Résidence</th>
                <td>{{ $reservation->residence->nom }}</td>
            </tr>
            <tr>
                <th>Type de chambre</th>
                <td>{{ $reservation->type->nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date d’arrivée</th>
                <td>{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Date de départ</th>
                <td>{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>{{ $reservation->statut }}</td>
            </tr>
        </table>

        <!-- RÉCAPITULATIF -->
        <h3>Résumé du paiement</h3>
        <table class="summary" cellspacing="0" cellpadding="0">
            <tr>
                <td>Sous-total</td>
                <td>{{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td>Taxes (10%)</td>
                <td>{{ number_format($reservation->total_price * 0.10, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr class="total">
                <td>Total</td>
                <td>{{ number_format($reservation->total_price * 1.10, 0, ',', ' ') }} FCFA</td>
            </tr>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            Merci pour votre confiance. Nous espérons vous revoir bientôt !<br>
            Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>
</body>

</html>