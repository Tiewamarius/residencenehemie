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
                        Réservations
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="connections" class="profile-nav-item">
                        <i class="fas fa-comment-dots"></i>
                        Review
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
                <a href="#" class="btn-light" style=" background-color: grey;">Completer profile</a>
            </div>

            <div class=" profils">
                @auth
                <div class="profile-info">
                    <img src="{{ auth()->user()->profile_picture ?? 'Non renseigné' }}" alt="Profile Picture" class="profile-photo">
                    <div class="profile-text">
                        <h3>{{ auth()->user()->name }}</h3>
                        <p class="role">{{ auth()->user()->role ?? 'Utilisateur' }}</p>
                        <p><i class="fas fa-phone-alt"></i> {{ auth()->user()->phone_number ?? 'Non renseigné' }}</p>
                    </div>
                </div>
                @else
                <div class="empty-message">
                    Veuillez vous connecter pour voir votre profil.
                </div>
                @endauth
            </div>

            {{-- Rappel profil --}}
            <div class="profile-reminder">
                <div>
                    <h4>Complétez votre profil</h4>
                    <p>Votre profil joue un rôle important dans chaque réservation.</p>
                </div>
                <!-- <button class="btn-primary">Commencer</button> -->
            </div>

            {{-- Commentaires --}}
            <div class="profile-comments">
                <h2>Commentaires que j'ai rédigés</h2>
                <div class="empty-message">
                    <i class="fas fa-comment-dots"></i>
                    <p>Aucun commentaire rédigé pour le moment.</p>
                </div>
            </div>

            {{-- Formulaire update --}}
            <div class="formulaire_update">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <label>Nom</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">

                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}">

                    <label>Téléphone</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">

                    <label>Adresse</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}">

                    <label>Description</label>
                    <textarea name="description">{{ old('description', $user->description) }}</textarea>

                    <label>Mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="password">
                    <input type="password" name="password_confirmation">

                    <label>Photo de profil</label>
                    <input type="file" name="profile_picture">
                    @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" width="80">
                    @endif

                    <button type="submit">Mettre à jour</button>
                </form>
            </div>
        </div>

        {{-- Onglet Réservations --}}
        <div id="reservations" class="tab-content">
            <h2>Réservations précédentes</h2>

            {{-- Filtres --}}
            <div class="filters">
                <input type="search" id="search-input" placeholder="Rechercher par numéro de réservation...">
                <select id="status-filter">
                    <option value="all">Tous les statuts</option>
                    <option value="Attente">En attente</option>
                    <option value="Confirmé">Confirmée</option>
                    <option value="Annulé">Annulée</option>
                    <option value="Terminé">Terminée</option>
                </select>
            </div>

            {{-- Conteneur qui sera mis à jour par AJAX --}}
            <div id="reservations-container">
                @include('profile.reservations_table', ['reservations' => $reservations])
            </div>
        </div>

        {{-- Onglet Review --}}
        <div id="connections" class="tab-content">
            <h2>Mes avis</h2>
            @if($reservations->where('statut', 'completed')->count() > 0)
            @foreach($reservations->where('statut', 'completed') as $booking)
            @if(!$booking->review()->where('user_id', auth()->id())->exists())
            <div class="review-form mb-4 p-3 border rounded">
                <h4>Laisser un avis pour la résidence : {{ $booking->residence->nom }}</h4>
                {{-- Messages de succès --}}
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                {{-- Messages d'erreur --}}
                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Veuillez corriger les erreurs ci-dessous :
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <style>
                    .alert {
                        padding: 12px 15px;
                        margin-bottom: 20px;
                        border-radius: 6px;
                        font-size: 14px;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }

                    .alert-success {
                        background: #d4edda;
                        border: 1px solid #c3e6cb;
                        color: #155724;
                    }

                    .alert-danger {
                        background: #f8d7da;
                        border: 1px solid #f5c6cb;
                        color: #721c24;
                    }

                    .alert i {
                        font-size: 16px;
                    }
                </style>
                <form action="{{ route('review.store', $booking->residence) }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                    <label for="rating-{{ $booking->id }}">Votre note :</label>
                    <div class="star-rating" data-target="rating-{{ $booking->id }}">
                        @for($i=1; $i<=5; $i++)
                            <i class="far fa-star" data-value="{{ $i }}"></i>
                            @endfor
                    </div>
                    <input type="hidden" name="note" id="rating-{{ $booking->id }}" required>

                    <label for="commentaire-{{ $booking->id }}">Votre commentaire :</label>
                    <textarea name="commentaire" id="commentaire-{{ $booking->id }}" required></textarea>

                    <button type="submit" class="btn-primary mt-2">Laisser un avis</button>
                </form>
            </div>
            @endif
            @endforeach
            @else
            <p>Aucune réservation terminée pour l’instant, vous pourrez laisser un avis après vos séjours.</p>
            @endif
        </div>
    </section>
</main>

@endsection