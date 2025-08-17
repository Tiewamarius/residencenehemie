@extends('layouts.myapp')

@section('title', 'Confirmation de la réservation')

@section('content')

<!-- Conteneur principal -->
<main class="container mx-auto mt-8 p-6 lg:p-10 flex flex-col lg:flex-row gap-8">
    <!-- Section de gauche - Détails du voyage et paiement -->
    <div class="w-full lg:w-3/5">

        {{-- Vérifie et affiche les messages de succès --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Vérifie et affiche les messages d'erreur de validation --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- 1. Votre voyage -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">1. Votre voyage</h2>
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    Dates :
                    <span class="font-normal">{{ \Carbon\Carbon::parse($check_in_date)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($check_out_date)->format('d/m/Y') }}</span>
                </h3>
                <h3 class="font-semibold text-lg text-gray-800 mt-2">
                    Voyageurs :
                    <span class="font-normal">{{ $guests }} personne(s)</span>
                </h3>
            </div>
        </div>

        <!-- 2. Paiement -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">2. Paiement</h2>
            <div class="mt-4">
                <p class="text-gray-700 mb-4">
                    Sélectionnez votre mode de paiement.
                </p>
                {{-- Formulaire de paiement fictif --}}
                <form id="payment-form" action="{{ route('residences.book', $residence->id) }}" method="POST">
                    @csrf

                    {{-- Champs cachés pour passer les données du voyage --}}
                    <input type="hidden" name="check_in_date" value="{{ $check_in_date }}">
                    <input type="hidden" name="check_out_date" value="{{ $check_out_date }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">

                    <div class="mb-4">
                        <label for="card_number" class="block text-gray-700 text-sm font-bold mb-2">Numéro de carte</label>
                        <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4 flex gap-4">
                        <div class="w-1/2">
                            <label for="expiration_date" class="block text-gray-700 text-sm font-bold mb-2">Date d'expiration</label>
                            <input type="text" id="expiration_date" name="expiration_date" placeholder="MM/AA" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div class="w-1/2">
                            <label for="cvv" class="block text-gray-700 text-sm font-bold mb-2">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="XXX" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>

                    {{-- Bouton de soumission du formulaire --}}
                    <button type="submit" class="w-full bg-[#FF385C] text-white font-semibold py-3 px-6 rounded-md hover:bg-[#E0004E] transition-colors duration-200">
                        Confirmer et payer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Section de droite - Détails de la réservation -->
    <div class="w-full lg:w-2/5">
        <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
            <!-- Détails de la chambre dynamiques -->
            <div class="flex items-center gap-4 border-b border-gray-200 pb-4 mb-4">
                {{-- Affiche l'image principale de la résidence --}}
                <img src="{{ optional($residence->images->first())->url ?: 'https://placehold.co/100x80' }}" alt="Image de la chambre" class="w-24 h-20 object-cover rounded-md">
                <div>
                    <h3 class="font-semibold text-lg">{{ $residence->name }}</h3>
                    <div class="flex items-center text-gray-600 mt-1">
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                        <span class="ml-1 text-sm font-medium">{{ $residence->rating ?? 'N/A' }}</span>
                        <span class="ml-1 text-xs text-gray-500">({{ $residence->review_count ?? '0' }})</span>
                    </div>
                </div>
            </div>

            <!-- Détails du paiement dynamiques -->
            <div class="border-b border-gray-200 pb-4 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold">Détails du prix</h4>
                </div>

                @php
                // Calcule le nombre de nuits
                $checkIn = \Carbon\Carbon::parse($check_in_date);
                $checkOut = \Carbon\Carbon::parse($check_out_date);
                $nights = $checkIn->diffInDays($checkOut);

                // Calcule le prix total
                $totalPrice = $residence->price_per_night * $nights;
                @endphp

                <ul class="mt-4 space-y-2 text-sm">
                    <li class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $residence->price_per_night }} € x {{ $nights }} nuits</span>
                        <span class="font-semibold">{{ $totalPrice }} €</span>
                    </li>
                    <li class="flex justify-between items-center text-gray-500">
                        <span>Frais de service</span>
                        <span class="font-semibold">50 €</span> {{-- Exemple de frais fixes --}}
                    </li>
                </ul>
            </div>

            <!-- Total final -->
            <div class="flex justify-between items-center font-bold text-lg">
                <span>Total (EUR)</span>
                <span>{{ $totalPrice + 50 }} €</span>
            </div>
        </div>
    </div>
</main>
@endsection