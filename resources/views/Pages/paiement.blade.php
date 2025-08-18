@extends('layouts.myapp')

@section('title', 'Paiement - Résidences Nehemie')

@section('content')

<main class="payment-main-content">
    <div class="container">
        <div class="payment-container">
            <!-- Colonne de gauche : Formulaire de paiement -->
            <div class="payment-left-column">
                <a href="{{ route('residences.detailsAppart', $residence->residence->id) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Demande de réservation</h1>

                <section class="payment-method-section">
                    <h2>1. Choisissez un mode de paiement</h2>
                    <form id="payment-form" action="{{ route('paiements.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $residence->id }}">
                        <input type="hidden" name="total_price" value="{{ $residence->total_price }}">

                        <!-- Option 1: Carte de crédit ou de débit -->
                        <div class="payment-option card-payment">
                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="carte" checked>
                                Carte de crédit ou de débit
                            </label>
                            <div class="card-details">
                                <div class="form-group">
                                    <label for="card-number">Numéro de carte</label>
                                    <input type="text" id="card-number" placeholder="0000 0000 0000 0000" disabled>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="expiry-date">Expiration</label>
                                        <input type="text" id="expiry-date" placeholder="MM/AA" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="cvv">Cryptogramme</label>
                                        <input type="text" id="cvv" placeholder="123" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="postal-code">Code postal</label>
                                    <input type="text" id="postal-code" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="country">Pays/région</label>
                                    <select id="country" disabled>
                                        <option value="CI">Côte d'Ivoire</option>
                                        <option value="FR">France</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Option 2: PayPal -->
                        <div class="payment-option">
                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="paypal">
                                PayPal
                            </label>
                        </div>

                        <!-- Option 3: Mobile Money / Autres -->
                        <div class="payment-option">
                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="mobile_money">
                                Mobile Money
                            </label>
                        </div>

                        <button type="submit" class="submit-payment-btn">Confirmer et payer</button>
                    </form>
                </section>
            </div>

            <!-- Colonne de droite : Récapitulatif de la réservation -->
            <div class="payment-right-column">
                <div class="booking-summary-card">
                    <div class="residence-summary">
                        <img src="{{ asset($residence->residence->images->where('est_principale', true)->first()->chemin_image) ?? 'https://placehold.co/100x100' }}" alt="Image de la résidence">
                        <div>
                            <h4>{{ $residence->residence->nom }}</h4>
                            <span class="rating">
                                <i class="fas fa-star"></i> {{ number_format($residence->residence->reviews->avg('note'), 2) ?? 'N/A' }} ({{ $residence->residence->reviews->count() }} avis)
                            </span>
                        </div>
                    </div>

                    <div class="travel-info">
                        <h3>Informations sur le voyage</h3>
                        <p>
                            <span>Dates</span>
                            <span>{{ \Carbon\Carbon::parse($residence->date_arrivee)->translatedFormat('d F') }} - {{ \Carbon\Carbon::parse($residence->date_depart)->translatedFormat('d F Y') }}</span>
                        </p>
                        <p>
                            <span>Voyageurs</span>
                            <span>{{ $residence->nombre_adultes }} adulte(s)</span>
                        </p>
                    </div>

                    <div class="price-details">
                        <h3>Détail du prix</h3>
                        <p>
                            <span>{{ number_format($residence->residence->types->min('prix_base'), 0, ',', ' ') }} FCFA x {{ \Carbon\Carbon::parse($residence->date_arrivee)->diffInDays($residence->date_depart) }} nuit(s)</span>
                            <span>{{ number_format($residence->total_price - 10000, 0, ',', ' ') }} FCFA</span>
                        </p>
                        <p>
                            <span>Frais de service</span>
                            <span>10 000 FCFA</span>
                        </p>
                        <p class="total">
                            <span>Total</span>
                            <span>{{ number_format($residence->total_price, 0, ',', ' ') }} FCFA</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection