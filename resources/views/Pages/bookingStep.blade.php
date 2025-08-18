@extends('layouts.myapp')

@section('title', 'Réserver - ' . $residence->name)

@section('content')

<main class="container mx-auto mt-8 p-6 lg:p-10 flex flex-col lg:flex-row gap-8">
    <!-- Section de gauche - Formulaire de réservation -->
    <div class="w-full lg:w-3/5">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Confirmer et payer</h1>

        <!-- Formulaire de réservation -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Votre voyage</h2>
            <div class="mb-4">
                <p class="text-gray-700">Dates : Du 1er déc. au 7 déc. 2024</p>
                <p class="text-gray-700">Nombre de voyageurs : 2 adultes</p>
                <a href="#" class="text-sm font-semibold underline hover:no-underline text-[#FF385C]">Modifier</a>
            </div>

            <div class="border-t border-gray-200 pt-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Modes de paiement</h2>
                <!-- Ajoutez ici les options de paiement (carte de crédit, Mobile Money, etc.) -->
                <p class="text-gray-500">
                    Les paiements sont sécurisés et traités par nos partenaires.
                </p>
            </div>

            <form action="#" method="POST" id="booking-form" class="mt-6">
                <!-- Champs de formulaire de paiement -->
                <!-- Par exemple, un champ pour le numéro de carte -->
                <div class="mb-4">
                    <label for="card-number" class="block text-gray-700 font-medium mb-2">Numéro de carte</label>
                    <input type="text" id="card-number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-[#FF385C] focus:border-[#FF385C]" placeholder="•••• •••• •••• ••••">
                </div>

                <!-- Bouton de réservation -->
                <button type="submit" class="w-full bg-[#FF385C] text-white font-semibold py-3 px-6 rounded-md hover:bg-[#E0004E] transition-colors duration-200">
                    Confirmer et payer
                </button>
            </form>
        </div>
    </div>

    <!-- Section de droite - Détails de la réservation -->
    <div class="w-full lg:w-2/5">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Détails de la réservation</h2>
            <div class="flex items-start gap-4 border-b border-gray-200 pb-4 mb-4">
                <img src="{{ $residence->images->first()->url }}" alt="Image de la résidence" class="w-24 h-20 object-cover rounded-md">
                <div>
                    <h3 class="font-semibold text-lg">{{ $residence->name }}</h3>
                    <p class="text-gray-600 mt-1">{{ $residence->city }}</p>
                    <div class="flex items-center text-gray-600 mt-1">
                        <!-- Assurez-vous que le modèle Residence a une relation reviews pour calculer la note -->
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                        <span class="ml-1 text-sm font-medium">{{ number_format($residence->reviews->avg('rating'), 2) }}</span>
                        <span class="ml-1 text-xs text-gray-500">({{ $residence->reviews->count() }} avis)</span>
                    </div>
                </div>
            </div>

            <!-- Résumé des prix -->
            <h3 class="font-semibold text-lg mb-4">Récapitulatif des prix</h3>
            <ul class="space-y-2 text-sm">
                <li class="flex justify-between items-center text-gray-700">
                    <span>{{ $residence->types->first()->price_par_nuit }} € x 6 nuits</span>
                    <span>{{ $residence->types->first()->price_par_nuit * 6 }} €</span>
                </li>
                <li class="flex justify-between items-center text-gray-700">
                    <span>Frais de service</span>
                    <span>50 €</span>
                </li>
            </ul>

            <div class="border-t border-gray-200 pt-4 mt-4 flex justify-between items-center text-lg font-bold">
                <span>Total</span>
                <span>{{ ($residence->types->first()->price_par_nuit * 6) + 50 }} €</span>
            </div>
        </div>
    </div>
</main>

@endsection