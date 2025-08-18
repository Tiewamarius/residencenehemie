@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie')

@section('content')

{{-- Les classes Tailwind remplacent le code CSS "pur" pour le style. --}}

{{-- Zone de contenu principal --}}
<main class="flex-grow container mx-auto px-6 py-8 flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8">

    {{-- Barre latérale gauche / Navigation --}}
    <aside class="w-full lg:w-1/4 bg-white rounded-xl shadow-md p-6 h-fit">
        <h2 class="text-2xl font-semibold mb-6 text-gray-900">Profil</h2>
        <nav>
            <ul>
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
        {{-- Contenu de l'onglet pour "À propos de moi" --}}
        <div id="about-me" class="tab-content bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">À propos de moi</h2>
                <a href="#" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                    Modifier
                </a>
            </div>

            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                <div class="flex-shrink-0">
                    <img src="https://placehold.co/120x120/007BFF/FFFFFF?text=Profile" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover shadow-lg">
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-xl font-semibold text-gray-900 mt-2 md:mt-0">John Doe</h3>
                    <p class="text-gray-600 text-sm">Utilisateur</p>
                    <p class="text-gray-500 mt-4">Aucune description disponible.</p>
                    <p class="text-gray-700 mt-2"><i class="fas fa-phone-alt mr-2"></i>+33 1 23 45 67 89</p>
                    <p class="text-gray-700 mt-2"><i class="fas fa-map-marker-alt mr-2"></i>123 Rue de la Paix, Paris</p>
                </div>
            </div>

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

        {{-- Contenu de l'onglet pour "Réservations précédentes" --}}
        <div id="reservations" class="tab-content bg-white rounded-xl shadow-md p-6 hidden" style="display:nfone;">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900">Réservations précédentes</h2>
            <div class="text-gray-500 text-center py-10">
                <p>Contenu des réservations précédentes...</p>
            </div>
        </div>

        {{-- Contenu de l'onglet pour "Connexions" --}}
        <div id="connections" class="tab-content bg-white rounded-xl shadow-md p-6" style="display:ncone;">
            <h2 class=" text-2xl font-semibold mb-6 text-gray-900">Connexions</h2>
            <div class="text-gray-500 text-center py-10">
                <p>Contenu des connexions...</p>
            </div>
        </div>
    </section>
</main>


@endsection