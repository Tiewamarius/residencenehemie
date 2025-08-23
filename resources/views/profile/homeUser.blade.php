@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie')

@section('content')
<main class="profile-container">
    {{-- Barre latérale gauche --}}
    <aside class="profile-sidebar">
        <h2 class="sidebar-title">Profil</h2>
        <nav>
            <ul>
                <li>
                    <a href="#" data-tab="about-me" class="profile-nav-item active">
                        <i class="fas fa-user-circle"></i>
                        À propos de moi
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="reservations" class="profile-nav-item">
                        <i class="fas fa-suitcase-rolling"></i>
                        Réservations précédentes
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="connections" class="profile-nav-item">
                        <i class="fas fa-users"></i>
                        Connexions
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- Contenu principal --}}
    <section class="profile-content">
        {{-- Onglet À propos de moi --}}
        <div id="about-me" class="tab-content active">
            <div class="tab-header">
                <h2>À propos de moi</h2>
                <a href="#" class="btn-light">Modifier</a>
            </div>

            @auth
            <div class="profile-info">
                <img src="{{ auth()->user()->profile_photo_url ?? 'https://placehold.co/120x120/007BFF/FFFFFF?text=Profile' }}" alt="Profile Picture" class="profile-photo">
                <div class="profile-text">
                    <h3>{{ auth()->user()->name }}</h3>
                    <p class="role">{{ auth()->user()->role ?? 'Utilisateur' }}</p>
                    <p class="bio">{{ auth()->user()->bio ?? 'Aucune description disponible.' }}</p>
                    <p><i class="fas fa-phone-alt"></i> {{ auth()->user()->phone_number ?? 'Non renseigné' }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> {{ auth()->user()->address ?? 'Non renseigné' }}</p>
                </div>
            </div>
            @else
            <div class="empty-message">
                Veuillez vous connecter pour voir votre profil.
            </div>
            @endauth

            <div class="profile-reminder">
                <div>
                    <h4>Complétez votre profil</h4>
                    <p>Votre profil joue un rôle important dans chaque réservation.</p>
                </div>
                <button class="btn-primary">Commencer</button>
            </div>

            <div class="profile-comments">
                <h2>Commentaires que j'ai rédigés</h2>
                <div class="empty-message">
                    <i class="fas fa-comment-dots"></i>
                    <p>Aucun commentaire rédigé pour le moment.</p>
                </div>
            </div>
        </div>

        {{-- Onglet Réservations --}}
        <div id="reservations" class="tab-content">
            <h2>Réservations précédentes</h2>

            {{-- Filtres --}}
            <div class="filters">
                <input type="text" id="search-input" placeholder="Rechercher par numéro de réservation...">
                <select id="status-filter">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmée</option>
                    <option value="cancelled">Annulée</option>
                    <option value="completed">Terminée</option>
                </select>
            </div>

            {{-- Tableau --}}
            <div class="table-wrapper">
                <table id="reservations-table">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Résidence</th>
                            <th>Date arrivée</th>
                            <th>Date départ</th>
                            <th>Prix total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($reservations) && $reservations->count() > 0)
                        @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->numero_reservation }}</td>
                            <td>{{ $reservation->residence->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_depart)->format('d/m/Y') }}</td>
                            <td>{{ number_format($reservation->total_price, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="status 
                                    @if($reservation->statut === 'En attente') pending 
                                    @elseif($reservation->statut === 'Confirmée') confirmed 
                                    @elseif($reservation->statut === 'Annulée') cancelled 
                                    @elseif($reservation->statut === 'Terminée') completed 
                                    @endif">
                                    {{ $reservation->statut }}
                                </span>
                            </td>
                            <td><a href="{{ route('bookings.show', $reservation->id) }}" class="link">Détails</a></td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="empty-message">Aucune réservation trouvée pour le moment.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if (!empty($reservations) && $reservations instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="pagination">
                {{ $reservations->links() }}
            </div>
            @endif
        </div>

        {{-- Onglet Connexions --}}
        <div id="connections" class="tab-content">
            <h2>Connexions</h2>
            <div class="empty-message">
                <p>Contenu des connexions...</p>
            </div>
        </div>
    </section>
</main>
@endsection