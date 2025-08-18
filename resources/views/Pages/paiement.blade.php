<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - Résidences Nehemie</title>
    <!-- Chargement de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chargement de Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Police de caractères Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style></style>
</head>

<body>

    @extends('layouts.myapp')

    @section('title', 'Paiement - Résidences Nehemie')

    @section('content')

    <main class="payment-main-content">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row gap-8 max-w-6xl mx-auto bg-white shadow-xl rounded-2xl p-6 md:p-10">
                <!-- Colonne de gauche : Formulaire de paiement -->
                <div class="flex-1">
                    <a href="{{ route('residences.detailsAppart', $booking->residence->id) }}" class="text-gray-600 hover:text-gray-900 transition-colors duration-200 mb-6 inline-block">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Demande de réservation</h1>

                    <section class="payment-method-section">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">1. Choisissez un mode de paiement</h2>
                        <form id="payment-form" action="{{ route('paiements.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <input type="hidden" name="total_price" value="{{ $booking->total_price }}">

                            <!-- Option 1: Carte de crédit ou de débit -->
                            <div class="payment-option border border-gray-300 rounded-xl p-4 mb-4 transition-all duration-200 cursor-pointer hover:border-blue-500">
                                <label class="payment-label">
                                    <input type="radio" name="payment_method" value="carte" checked>
                                    Carte de crédit ou de débit
                                </label>
                                <div class="card-details mt-4 pt-4 border-t border-gray-200 hidden">
                                    <div class="form-group mb-4">
                                        <label for="card-number" class="block text-sm font-medium text-gray-600 mb-1">Numéro de carte</label>
                                        <input type="text" id="card-number" placeholder="0000 0000 0000 0000" disabled class="w-full p-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                    <div class="form-row flex gap-4">
                                        <div class="form-group flex-1 mb-4">
                                            <label for="expiry-date" class="block text-sm font-medium text-gray-600 mb-1">Expiration</label>
                                            <input type="text" id="expiry-date" placeholder="MM/AA" disabled class="w-full p-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                        </div>
                                        <div class="form-group flex-1 mb-4">
                                            <label for="cvv" class="block text-sm font-medium text-gray-600 mb-1">Cryptogramme</label>
                                            <input type="text" id="cvv" placeholder="123" disabled class="w-full p-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="postal-code" class="block text-sm font-medium text-gray-600 mb-1">Code postal</label>
                                        <input type="text" id="postal-code" disabled class="w-full p-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="country" class="block text-sm font-medium text-gray-600 mb-1">Pays/région</label>
                                        <select id="country" disabled class="w-full p-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 disabled:bg-gray-100 disabled:cursor-not-allowed">
                                            <option value="CI">Côte d'Ivoire</option>
                                            <option value="FR">France</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: PayPal -->
                            <div class="payment-option border border-gray-300 rounded-xl p-4 mb-4 transition-all duration-200 cursor-pointer hover:border-blue-500">
                                <label class="payment-label">
                                    <input type="radio" name="payment_method" value="paypal">
                                    PayPal
                                </label>
                            </div>

                            <!-- Option 3: Mobile Money / Autres -->
                            <div class="payment-option border border-gray-300 rounded-xl p-4 mb-4 transition-all duration-200 cursor-pointer hover:border-blue-500">
                                <label class="payment-label">
                                    <input type="radio" name="payment_method" value="mobile_money">
                                    Mobile Money
                                </label>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl mt-6 hover:bg-blue-700 transition-colors duration-200 shadow-md">Confirmer et payer</button>
                        </form>
                    </section>
                </div>

                <!-- Colonne de droite : Récapitulatif de la réservation -->
                <div class="flex-1">
                    <div class="booking-summary-card bg-gray-50 rounded-2xl p-6 shadow-inner">
                        <div class="residence-summary flex items-center gap-4 border-b border-gray-200 pb-6 mb-6">
                            <img src="{{ asset($booking->residence->images->where('est_principale', true)->first()->chemin_image) ?? 'https://placehold.co/100x100' }}" alt="Image de la résidence" class="w-20 h-20 object-cover rounded-xl">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800">{{ $booking->residence->nom }}</h4>
                                <span class="rating text-sm text-yellow-500">
                                    <i class="fas fa-star"></i> {{ number_format($booking->residence->reviews->avg('note'), 2) ?? 'N/A' }} ({{ $booking->residence->reviews->count() }} avis)
                                </span>
                            </div>
                        </div>

                        <div class="travel-info">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 mt-6">Informations sur le voyage</h3>
                            <p class="flex justify-between items-center text-gray-600 mb-2">
                                <span class="font-medium">Dates</span>
                                <span>{{ \Carbon\Carbon::parse($booking->date_arrivee)->translatedFormat('d F') }} - {{ \Carbon\Carbon::parse($booking->date_depart)->translatedFormat('d F Y') }}</span>
                            </p>
                            <p class="flex justify-between items-center text-gray-600 mb-2">
                                <span class="font-medium">Adult(s)s</span>
                                <span>{{ $booking->nombre_adultes }} adulte(s)</span>
                            </p>
                            <p class="flex justify-between items-center text-gray-600 mb-2">
                                <span class="font-medium">Enfants</span>
                                <span>{{ $booking->nombre_enfants }} enfant(s)</span>
                            </p>
                        </div>

                        <div class="price-details border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-lg font-semibold text-gray-700 mb-3 mt-6">Détail du prix</h3>
                            <p class="flex justify-between items-center text-gray-600 mb-2">
                                <span class="font-medium">{{ number_format($booking->type->prix_base, 0, ',', ' ') }} FCFA x {{ \Carbon\Carbon::parse($booking->date_arrivee)->diffInDays($booking->date_depart) }} nuit(s)</span>
                                <span>{{ number_format($booking->total_price + 10000, 0, ',', ' ') }} FCFA</span>
                            </p>
                            <p class="flex justify-between items-center text-gray-600 mb-2">
                                <span class="font-medium">Frais de service</span>
                                <span>10 000 FCFA</span>
                            </p>
                            <p class="total text-xl font-bold text-gray-800 mt-4 flex justify-between items-center">
                                <span>Total</span>
                                <span>{{ number_format($booking->total_price, 0, ',', ' ') }} FCFA</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endsection
</body>

</html>