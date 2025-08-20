 @extends('layouts.myapp')

 @section('title', 'Paiement - Résidences Nehemie')

 @section('content')

 <main class="payment-main-content">
     <div class="container mx-auto">
         <div class="payment-container">
             <!-- Colonne de gauche : Formulaire de paiement -->
             <div class="payment-left-column">
                 <a href="{{ route('residences.detailsAppart', $booking->residence->id) }}" class="back-link">
                     <i class="fas fa-arrow-left"></i>
                 </a>
                 <h1>Demande de réservation</h1>

                 <section class="payment-method-section">
                     <h2>1. Choisissez un mode de paiement</h2>
                     <form id="payment-form" action="{{ route('paiements.process') }}" method="POST">
                         @csrf
                         <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                         <input type="hidden" name="total_price" value="{{ $booking->total_price }}">

                         <!-- Option 1: Carte de crédit ou de débit -->
                         <div class="payment-option card-payment">
                             <label class="payment-label">
                                 <input type="radio" name="payment_method" value="carte">
                                 Carte de crédit ou de débit
                             </label>
                             <div class="payment-details card-details">
                                 <div class="form-group">
                                     <label for="card-number">Numéro de carte</label>
                                     <input type="text" id="card-number" placeholder="0000 0000 0000 0000">
                                 </div>
                                 <div class="form-row">
                                     <div class="form-group">
                                         <label for="expiry-date">Expiration</label>
                                         <input type="text" id="expiry-date" placeholder="MM/AA">
                                     </div>
                                     <div class="form-group">
                                         <label for="cvv">Cryptogramme</label>
                                         <input type="text" id="cvv" placeholder="123">
                                     </div>
                                 </div>
                                 <div class="form-group">
                                     <label for="postal-code">Code postal</label>
                                     <input type="text" id="postal-code">
                                 </div>
                                 <div class="form-group">
                                     <label for="country">Pays/région</label>
                                     <select id="country">
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
                             <!-- Pas de champs de saisie pour PayPal -->
                             <div class="payment-details paypal-details hidden">
                                 <p>Vous serez redirigé vers la page de paiement sécurisé de PayPal.</p>
                             </div>
                         </div>

                         <!-- Option 3: Mobile Money -->
                         <div class="payment-option mobile-money-option">
                             <label class="payment-label">
                                 <input type="radio" name="payment_method" value="mobile_money">
                                 Mobile Money
                             </label>
                             <div class="payment-details mobile-money-details hidden">
                                 <div class="form-group">
                                     <label for="mobile-money-phone">Numéro de téléphone Mobile Money</label>
                                     <input type="tel" id="mobile-money-phone" name="mobile_money_phone" placeholder="Ex: 0707070707">
                                 </div>
                             </div>
                         </div>

                         <!-- Option 4: Wave -->
                         <div class="payment-option wave-option">
                             <label class="payment-label">
                                 <input type="radio" name="payment_method" value="wave">
                                 Wave
                             </label>
                             <div class="payment-details wave-details hidden">
                                 <div class="form-group">
                                     <label for="wave-phone">Numéro de téléphone Wave</label>
                                     <input type="tel" id="wave-phone" name="wave_phone" placeholder="Ex: 0707070707">
                                 </div>
                             </div>
                         </div>

                         <!-- Option 5: Paiement en espèce -->
                         <div class="payment-option">
                             <label class="payment-label">
                                 <input type="radio" name="payment_method" value="espece">
                                 Paiement en espèce
                             </label>
                             <!-- Pas de champs de saisie pour le paiement en espèce -->
                             <div class="payment-details espece-details hidden">
                                 <p>Un agent vous contactera pour organiser le paiement en espèce.</p>
                             </div>
                         </div>

                         <button type="submit" class="submit-payment-btn">Confirmer et payer</button>
                     </form>
                 </section>
             </div>

             <!-- Colonne de droite : Récapitulatif de la réservation -->
             <div class="payment-right-column">
                 <div class="booking-summary-card">
                     <div class="residence-summary">
                         {{-- Correction de l'erreur "Attempt to read property on null" --}}
                         {{-- On vérifie d'abord si l'image principale existe avant d'accéder à sa propriété 'chemin_image'. --}}
                         @php
                         $mainImage = $booking->residence->images->where('est_principale', true)->first();
                         $imagePath = $mainImage ? asset($mainImage->chemin_image) : 'https://placehold.co/100x100';
                         @endphp
                         <img src="{{ $imagePath }}" alt="Image de la résidence" class="w-20 h-20 object-cover rounded-xl">
                         <div>
                             <h4>{{ $booking->residence->nom }}</h4>
                             <span class="rating">
                                 <i class="fas fa-star"></i> {{ number_format($booking->residence->reviews->avg('note'), 2) ?? 'N/A' }} ({{ $booking->residence->reviews->count() }} avis)
                             </span>
                         </div>
                     </div>

                     <div class="travel-info">
                         <h3>Informations sur le voyage</h3>
                         <p>
                             <span>Dates</span>
                             <span>{{ \Carbon\Carbon::parse($booking->date_arrivee)->translatedFormat('d F') }} - {{ \Carbon\Carbon::parse($booking->date_depart)->translatedFormat('d F Y') }}</span>
                         </p>
                         <p>
                             <span>Voyageurs</span>
                             <span>{{ $booking->nombre_adultes }} adulte(s)</span>
                         </p>
                     </div>

                     <div class="price-details">
                         <h3>Détail du prix</h3>
                         <p>
                             <span>{{ number_format($booking->type->prix_base, 0, ',', ' ') }} FCFA x {{ \Carbon\Carbon::parse($booking->date_arrivee)->diffInDays($booking->date_depart) }} nuit(s)</span>
                             <span>{{ number_format($booking->total_price - 10000, 0, ',', ' ') }} FCFA</span>
                         </p>
                         <p>
                             <span>Frais de service</span>
                             <span>10 000 FCFA</span>
                         </p>
                         <p class="total">
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