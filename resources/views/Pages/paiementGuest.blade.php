@extends('layouts.myapp')

@section('title', 'Paiement - Résidences Nehemie')

@section('content')
<main class="payment-main-content">

    <div class="container mx-auto">
        <div class="payment-container">
            <!-- Colonne gauche -->
            <div class="payment-left-column">
                <a href="{{ route('residences.detailsAppart', $booking->residence->id) }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1>Demande de réservation</h1>

                <section class="payment-method-section">
                    <h2>1. Choisissez un mode de paiement</h2>
                    <form id="payment-form" action="{{ route('paiements.finaliser') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="total_price" value="{{ $booking->total_price }}">

                        <!-- Carte (indisponible) -->
                        <div class="payment-option disabled-option" data-message="🚫 Paiement par carte indisponible.">
                            <label class="payment-label">
                                <img src="{{ asset('images/mastercard-hd-png.png') }}" alt="Carte" height="350px" class="payment-logo">
                                <input type="radio" name="payment_method" value="carte" disabled>
                                Carte bancaire
                            </label>
                        </div>

                        <!-- PayPal (indisponible) -->
                        <div class="payment-option disabled-option" data-message="🚫 Paiement via PayPal indisponible.">
                            <label class="payment-label">
                                <img src="{{ asset('images/paypal.jpg') }}" alt="PayPal" class="payment-logo">
                                <input type="radio" name="payment_method" value="paypal" disabled>
                                PayPal
                            </label>
                        </div>

                        <!-- Mobile Money -->
                        <div class="payment-option disabled-option">
                            <label class="payment-label" for="payment-mm">
                                <img src="{{ asset('images/orange-Money.jpg') }}" alt="Mobile Money" class="payment-logo">
                                <input type="radio" id="payment-mm" name="payment_method" value="mobile_money">
                                Orange Mobile Money
                            </label>
                            <div class="payment-details" id="mobile-money-details">
                                <div class="form-group">
                                    <label for="mobile-money-phone">Numéro Mobile Money</label>
                                    <input type="tel" id="mobile-money-phone" name="mobile_money_phone" placeholder="Ex: 0707070707" value="{{ old('mobile_money_phone') }}">
                                    @error('mobile_money_phone') <small class="text-red-500">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Wave -->
                        <div class="payment-option disabled-option">
                            <label class="payment-label" for="payment-wave">
                                <img src="{{ asset('images/WAVE-M.jpg') }}" alt="Wave" class="payment-logo">
                                <input type="radio" id="payment-wave" name="payment_method" value="wave">
                                Wave
                            </label>
                            <div class="payment-details" id="wave-details">
                                <div class="form-group">
                                    <label for="wave-phone">Numéro Wave</label>
                                    <input type="tel" id="wave-phone" name="wave_phone" placeholder="Ex: 0707070707" value="{{ old('wave_phone') }}">
                                    @error('wave_phone') <small class="text-red-500">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Espèce -->
                        <div class="payment-option">
                            <label class="payment-label {{ $hasUnpaidBooking ? 'disabled-label' : '' }}" for="payment-espece">
                                <img src="{{ asset('images/especes.png') }}" alt="Espèce" class="payment-logo">
                                <input type="radio" id="payment-espece" name="payment_method" value="espece"
                                    {{ $hasUnpaidBooking ? 'disabled' : '' }} required>
                                Paiement en espèce
                            </label>

                            <div class="payment-details" id="espece-details">
                                <p>Un agent vous contactera pour organiser le paiement en espèce.</p>

                                @if($hasUnpaidBooking)
                                <p class="warning-message" id="warning-message">
                                    ⚠️ Ce mode de paiement est bloqué car une réservation en attente d’espèces existe déjà pour cet appartement.
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="price-details">
                            @guest
                            <h3>Vos coordonnées</h3>
                            <div class="form-group">
                                <input type="text" id=" " name="name" placeholder="Nom " required>
                            </div>
                            <div class="form-group">
                                <input type="tel" id=" " name="phone_number" placeholder=" Numero" required>
                            </div>
                            <div class="form-group">
                                <input type="email" id=" " name="email" placeholder="Email" required>
                            </div>
                            @endguest
                            <button type="submit" class="button login-continue-btn">RESERVER</button>

                    </form>
            </div>

            </section>
        </div>

        <!-- Colonne droite -->
        <div class="payment-right-column">
            <div class="booking-summary-card">
                <div class="residence-summary">
                    @php
                    $mainImage = $booking->residence->images->where('est_principale', true)->first();
                    $imagePath = $mainImage ? asset($mainImage->chemin_image) : 'https://placehold.co/100x100';
                    @endphp
                    <img src="{{ $imagePath }}" alt="Image résidence" class="w-20 h-20 object-cover rounded-xl">
                    <div>
                        <h4>{{ $booking->residence->nom }}</h4>
                        <span class="rating">
                            <i class="fas fa-star"></i>
                            {{ $booking->residence->reviews->count() > 0 ? number_format($booking->residence->reviews->avg('note'), 2) : 'N/A' }}
                            ({{ $booking->residence->reviews->count() }} avis)
                        </span>
                    </div>
                </div>

                <div class="travel-info">
                    <h3>Informations sur le voyage</h3>
                    <p><span>Dates</span>
                        <span>{{ \Carbon\Carbon::parse($booking->date_arrivee)->translatedFormat('d F') }} - {{ \Carbon\Carbon::parse($booking->date_depart)->translatedFormat('d F Y') }}</span>
                    </p>
                    <p><span>Voyageurs</span> <span>{{ $booking->nombre_adultes }} adulte(s)</span></p>
                </div>

                <div class="price-details">
                    <h3>Détail du prix</h3>
                    <p>
                        <span>{{ number_format($booking->type->prix_base, 0, ',', ' ') }} FCFA x {{ \Carbon\Carbon::parse($booking->date_arrivee)->diffInDays($booking->date_depart) }} nuit(s)</span>
                        <span>{{ number_format($booking->total_price - 10000, 0, ',', ' ') }} FCFA</span>
                    </p>
                    <p><span>Frais de service</span> <span>10 000 FCFA</span></p>
                    <p class="total"><span>Total</span> <span>{{ number_format($booking->total_price, 0, ',', ' ') }} FCFA</span></p>
                </div>

            </div>
        </div>
    </div>
    </div>
</main>
@endsection