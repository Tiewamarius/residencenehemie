@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie')

@section('content')

{{-- Conteneur principal avec les classes Tailwind --}}
<main class="flex-grow container mx-auto px-6 py-8 flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8">

    {{-- Barre latérale gauche / Navigation --}}
    <aside class="w-full lg:w-1/4 bg-white rounded-xl shadow-md p-6 h-fit">
        <h2 class="text-2xl font-semibold mb-6 text-gray-900">Profil</h2>
        <nav>
            <ul>
                {{-- L'attribut 'data-tab' lie le lien à l'ID du contenu. --}}
                <li>
                    <a href="#" data-tab="about-me" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 active">
                        <i class="fas fa-user-circle text-xl mr-3"></i>
                        À propos de moi
                    </a>
                </li>
                <li class="mt-2">
                    <a href="#" data-tab="reservations" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-suitcase-rolling text-xl mr-3"></i>
                        Réservations précédentes
                    </a>
                </li>
                <li class="mt-2">
                    <a href="#" data-tab="connections" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-users text-xl mr-3"></i>
                        Connexions
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- Contenu principal de droite - Conteneur du contenu de l'onglet --}}
    <section class="w-full lg:w-3/4 flex flex-col space-y-8">
        {{-- Le script JavaScript gère maintenant la visibilité de l'onglet. --}}
        <div id="about-me" class="tab-content bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">À propos de moi</h2>
                <a href="#" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                    Modifier
                </a>
            </div>

            {{-- Utilisation de la directive @auth pour vérifier si l'utilisateur est connecté --}}
            @auth
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                <div class="flex-shrink-0">
                    {{-- Affichage de la photo de profil de l'utilisateur, ou d'une image par défaut --}}
                    <img src="{{ auth()->user()->profile_photo_url ?? 'https://placehold.co/120x120/007BFF/FFFFFF?text=Profile' }}" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover shadow-lg">
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-xl font-semibold text-gray-900 mt-2 md:mt-0">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ auth()->user()->role ?? 'Utilisateur' }}</p>
                    <p class="text-gray-500 mt-4">{{ auth()->user()->bio ?? 'Aucune description disponible.' }}</p>
                    <p class="text-gray-700 mt-2"><i class="fas fa-phone-alt mr-2"></i>{{ auth()->user()->phone_number ?? 'Non renseigné' }}</p>
                    <p class="text-gray-700 mt-2"><i class="fas fa-map-marker-alt mr-2"></i>{{ auth()->user()->address ?? 'Non renseigné' }}</p>
                </div>
            </div>
            @else
            {{-- Message affiché si l'utilisateur n'est pas connecté --}}
            <div class="text-center text-gray-500 py-10">
                Veuillez vous connecter pour voir votre profil.
            </div>
            @endauth

            <div class="mt-8 bg-pink-100 border border-pink-300 rounded-xl p-6 flex flex-col md:flex-row items-center md:justify-between space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <h4 class="text-lg font-semibold text-pink-800">Complétez votre profil</h4>
                    <p class="text-pink-700 text-sm mt-1">Votre profil joue un rôle important dans chaque réservation.</p>
                </div>
                <button class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition-colors duration-200 font-semibold shadow-md">
                    Commencer
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 mt-8">
                <h2 class="text-2xl font-semibold mb-6 text-gray-900">Commentaires que j'ai rédigés</h2>
                <div class="text-gray-500 text-center py-10">
                    <i class="fas fa-comment-dots text-5xl mb-4"></i>
                    <p>Aucun commentaire rédigé pour le moment.</p>
                </div>
            </div>
        </div>

        {{-- Le script JavaScript gère maintenant la visibilité de l'onglet. --}}
        <div id="reservations" class="tab-content bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900">Réservations précédentes</h2>

            <!-- Filtres et recherche -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1 w-full md:w-auto">
                    <label for="search-input" class="sr-only">Rechercher</label>
                    <div class="relative">
                        <input type="text" id="search-input" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Rechercher par numéro de réservation...">
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <label for="status-filter" class="sr-only">Filtrer par statut</label>
                    <select id="status-filter" class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="all">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="cancelled">Annulée</option>
                        <option value="completed">Terminée</option>
                    </select>
                </div>
            </div>

            <!-- Tableau des réservations -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="reservations-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro de réservation</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résidence</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'arrivée</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date de départ</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if (!empty($reservations) && $reservations->count() > 0)
                        @foreach ($reservations as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $reservation->numero_reservation }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $reservation->residence->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($reservation->statut === 'En attente')
                                    bg-yellow-100 text-yellow-800
                                @elseif($reservation->statut === 'Confirmée')
                                    bg-green-100 text-green-800
                                @elseif($reservation->statut === 'Annulée')
                                    bg-red-100 text-red-800
                                @endif">
                                    {{ $reservation->statut }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('bookings.show', $reservation->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Détails
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucune réservation trouvée pour le moment.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Ajout de la pagination --}}
            @if (!empty($reservations) && $reservations instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6">
                {{ $reservations->links() }}
            </div>
            @endif
        </div>


        {{-- Le script JavaScript gère maintenant la visibilité de l'onglet. --}}
        <div id="connections" class="tab-content bg-white rounded-xl shadow-md p-6">
            <h2 class=" text-2xl font-semibold mb-6 text-gray-900">Connexions</h2>
            <div class="text-gray-500 text-center py-10">
                <p>Contenu des connexions...</p>
            </div>
        </div>
    </section>
</main>

{{-- Inclure le script JavaScript ici pour gérer la logique des onglets --}}
@endsection