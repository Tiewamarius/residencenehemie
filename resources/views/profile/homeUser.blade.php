@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie') {{-- Titre spécifique à cette page --}}

@section('content')

<!-- Main Content Area -->
    <main class="flex-grow container mx-auto px-6 py-8 flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8">
        
        <!-- Left Sidebar / Navigation -->
        <aside class="w-full lg:w-1/4 bg-white rounded-xl shadow-md p-6 h-fit">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900">Profil</h2>
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('profile.homeUser') }}" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:text-gray-900 transition-colors duration-200 active">
                            <i class="fas fa-user-circle text-xl mr-3"></i>
                            À propos de moi
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="#" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-suitcase-rolling text-xl mr-3"></i>
                            Reservations précédentes
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="#" class="profile-nav-item flex items-center p-3 rounded-lg text-gray-700 hover:text-gray-900 transition-colors duration-200">
                            <i class="fas fa-users text-xl mr-3"></i>
                            Connexions
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Right Main Content -->
        <section class="w-full lg:w-3/4 flex flex-col space-y-8">
            <!-- About Me Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">À propos de moi</h2>
                    <a href="{{ route('profile.edit') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                        Modifier
                    </a>
                </div>
                
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $user->profile_picture ? asset($user->profile_picture) : 'https://placehold.co/120x120/007BFF/FFFFFF?text=Profile' }}" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover shadow-lg">
                    </div>
                    <div class="text-center md:text-left">
                        <h3 class="text-xl font-semibold text-gray-900 mt-2 md:mt-0">{{ $user->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ ucfirst($user->role ?? 'Utilisateur') }}</p>
                        @if($user->description)
                            <p class="text-gray-700 mt-4">{{ $user->description }}</p>
                        @else
                            <p class="text-gray-500 mt-4">Aucune description disponible.</p>
                        @endif
                        @if($user->phone_number)
                            <p class="text-gray-700 mt-2"><i class="fas fa-phone-alt mr-2"></i>{{ $user->phone_number }}</p>
                        @endif
                        @if($user->address)
                            <p class="text-gray-700 mt-2"><i class="fas fa-map-marker-alt mr-2"></i>{{ $user->address }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 bg-pink-100 border border-pink-300 rounded-xl p-6 flex flex-col md:flex-row items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="text-center md:text-left">
                        <h4 class="text-lg font-semibold text-pink-800">Complétez votre profil</h4>
                        <p class="text-pink-700 text-sm mt-1">Votre profil   joue un rôle important dans chaque réservation.</p>
                    </div>
                    <button class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition-colors duration-200 font-semibold shadow-md">
                        Commencer
                    </button>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-6 text-gray-900">Commentaires que j'ai rédigés</h2>
                @if($user->reviews->isEmpty()) {{-- Assuming a 'reviews' relationship on User model --}}
                    <div class="text-gray-500 text-center py-10">
                        <i class="fas fa-comment-dots text-5xl mb-4"></i>
                        <p>Aucun commentaire rédigé pour le moment.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($user->reviews as $review)
                            <div class="border-b pb-4 last:border-b-0">
                                <p class="text-gray-700">{{ $review->commentaire }}</p>
                                <p class="text-gray-500 text-sm mt-2">
                                    <i class="fas fa-star text-yellow-400"></i> {{ $review->note }}/5 &bull; {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y') }}
                                </p>
                                @if($review->residence)
                                    <p class="text-gray-600 text-sm mt-1">
                                        Pour : <a href="{{ route('residences.detailsAppart', $review->residence->id) }}" class="text-blue-600 hover:underline">{{ $review->residence->nom }}</a>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>
   
@endsection
</html>
