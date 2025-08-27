@extends('layouts.myapp')

@section('title', 'Confirmation de la réservation')

@section('content')

<main class="container mx-auto mt-8 p-6 lg:p-10 flex flex-col lg:flex-row gap-8">

    <!-- Section de gauche -->
    <div class="w-full lg:w-3/5">

        {{-- Succès --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Erreurs --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- 1. Informations personnelles (si non connecté) -->
        @guest
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">1. Vos informations</h2>
            <p class="text-gray-600 mt-2">Veuillez renseigner vos informations pour finaliser votre réservation.</p>

            <div class="mt-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Adresse e-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Numéro de téléphone</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>
            </div>
        </div>
        @endguest

        <!-- 2. Votre voyage -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">2. Votre voyage</h2>
            <div class="mt-4">
                <h3 class="font-semibold text-lg text-gray-800">
                    Dates :
                    <span class="font-normal">{{ \Carbon\Carbon::parse($date_arrivee)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($date_arrivee)->format('d/m/Y') }}</span>
                </h3>
                <h3 class="font-semibold text-lg text-gray-800 mt-2">
                    Voyageurs :
                    <span class="font-normal">{{ $guests }} personne(s)</span>
                </h3>
            </div>
        </div>

        <!-- 3. Paiement -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">3. Paiement</h2>
            <div class="mt-4">
                <p class="text-gray-700 mb-4">Sélectionnez votre mode de paiement.</p>

                <form id="payment-form" action="{{ route('residences.book', $residence->id) }}" method="POST">
                    @csrf

                    {{-- Champs cachés --}}
                    <input type="hidden" name="check_in_date" value="{{ $check_in_date }}">
                    <input type="hidden" name="check_out_date" value="{{ $check_out_date }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">

                    @guest
                    {{-- On repasse les infos utilisateur dans la requête --}}
                    <input type="hidden" name="name" id="hidden_name">
                    <input type="hidden" name="email" id="hidden_email">
                    <input type="hidden" name="phone_number" id="hidden_phone_number">
                    @endguest

                    <div class="mb-4">
                        <label for="card_number" class="block text-gray-700 text-sm font-bold mb-2">Numéro de carte</label>
                        <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>
                    <div class="mb-4 flex gap-4">
                        <div class="w-1/2">
                            <label for="expiration_date" class="block text-gray-700 text-sm font-bold mb-2">Date d'expiration</label>
                            <input type="text" id="expiration_date" name="expiration_date" placeholder="MM/AA"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                        <div class="w-1/2">
                            <label for="cvv" class="block text-gray-700 text-sm font-bold mb-2">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="XXX"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#FF385C] text-white font-semibold py-3 px-6 rounded-md hover:bg-[#E0004E] transition-colors duration-200">
                        Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Section droite - résumé -->
    <div class="w-full lg:w-2/5">
        <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
            <div class="flex items-center gap-4 border-b border-gray-200 pb-4 mb-4">
                <img src="{{ optional($residence->images->first())->url ?: 'https://placehold.co/100x80' }}" alt="Image"
                    class="w-24 h-20 object-cover rounded-md">
                <div>
                    <h3 class="font-semibold text-lg">{{ $residence->name }}</h3>
                    <div class="flex items-center text-gray-600 mt-1">
                        <i class="fas fa-star text-sm text-yellow-400"></i>
                        <span class="ml-1 text-sm font-medium">{{ $residence->rating ?? 'N/A' }}</span>
                        <span class="ml-1 text-xs text-gray-500">({{ $residence->review_count ?? '0' }})</span>
                    </div>
                </div>
            </div>

            @php
            $checkIn = \Carbon\Carbon::parse($check_in_date);
            $checkOut = \Carbon\Carbon::parse($check_out_date);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $residence->price_per_night * $nights;
            @endphp

            <div class="border-b border-gray-200 pb-4 mb-4">
                <h4 class="font-semibold">Détails du prix</h4>
                <ul class="mt-4 space-y-2 text-sm">
                    <li class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $residence->price_per_night }} € x {{ $nights }} nuits</span>
                        <span class="font-semibold">{{ $totalPrice }} €</span>
                    </li>
                    <li class="flex justify-between items-center text-gray-500">
                        <span>Frais de service</span>
                        <span class="font-semibold">50 €</span>
                    </li>
                </ul>
            </div>

            <div class="flex justify-between items-center font-bold text-lg">
                <span>Total (EUR)</span>
                <span>{{ $totalPrice + 50 }} €</span>
            </div>
        </div>
    </div>
</main>

{{-- Script pour passer les infos non inscrits dans le formulaire --}}
@guest
<script>
    document.getElementById('payment-form').addEventListener('submit', function() {
        document.getElementById('hidden_name').value = document.getElementById('name').value;
        document.getElementById('hidden_email').value = document.getElementById('email').value;
        document.getElementById('hidden_phone_number').value = document.getElementById('phone_number').value;
    });
</script>
@endguest

@endsection