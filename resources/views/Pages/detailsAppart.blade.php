<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Détails de l'Appartement</title>
    
    {{-- Inclure vos assets CSS et JS via Vite --}}
    @vite(['resources/css/detailsAppart.css', 'resources/js/detailsAppart.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"> {{-- Ajouté pour les icônes Unicons si utilisées --}}
    
</head>
<body>
    {{-- Sidebar (Menu Mobile) - Identique à HomePage --}}
    <div class="sidebar" id="sidebar">
        <div class="sidebar_header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Résidences Nehemie" class="sidebar_logo" onerror="this.onerror=null;this.src='https://placehold.co/150x50/FFFFFF/000000?text=LOGO';">
            <i class="fas fa-times sidebar_close_btn" id="sidebar-close-btn"></i>
        </div>
        <ul class="sidebar_nav_items">
            <li><a href="{{ route('HomePage') }}" class="sidebar_nav_link">Accueil</a></li>
            <li><a href="{{ route('residences.index') }}" class="sidebar_nav_link">Appartements</a></li>
            <li><a href="#" class="sidebar_nav_link" id="contact-open-sidebar-btn">Contact</a></li>
            <li><a href="" class="sidebar_nav_link">A propos</a></li>
            <li><a href="" class="sidebar_nav_link"><i class="fas fa-search"></i> Recherche</a></li>
            {{-- Liens d'authentification pour la sidebar --}}
            @guest
                <!-- <li><a href="{{ route('register') }}" class="sidebar_nav_link"><i class="fas fa-user-plus"></i> S'inscrire</a></li>
                <li><a href="{{ route('login') }}" class="sidebar_nav_link"><i class="fas fa-sign-in-alt"></i> Se connecter</a></li> -->
            @else
                <li><a href="{{ route('favorites.index') }}" class="sidebar_nav_link"><i class="fas fa-heart"></i> Favoris</a></li>
                <li><a href="{{ route('dashboard') }}" class="sidebar_nav_link"><i class="fas fa-user-circle"></i> Mon Compte</a></li>
                <li><a href="#" class="sidebar_nav_link" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a></li>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @endguest
            <li><button class="button sidebar_assistant_btn" id="form-open-sidebar">Assistant</button></li>
        </ul>
    </div>

    {{-- Header principal - Identique à HomePage --}}
    <header class="header">
        <nav class="nav">
            <a href="{{ route('HomePage') }}" class="nav_logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Résidences Nehemie" onerror="this.onerror=null;this.src='https://placehold.co/150x50/FFFFFF/000000?text=Votre+Logo';" style="height: 70px; width: auto;">
            </a>
            <ul class="nav_item">
                <li><a href="{{ route('HomePage') }}" class="nav_link">Accueil</a></li>
                <li><a href="{{ route('residences.index') }}" class="nav_link">Appartements</a></li>
                <li><a href="#" class="nav_link" id="contact_open_btn">Contact</a></li>
                <li><a href="" class="nav_link">A propos</a></li>
                <li><a href="" class="nav_link"><i class="fas fa-search"></i></a></li>
                
                @guest
                    {{-- Le lien Favoris ouvre la modale de connexion si l'utilisateur n'est pas connecté --}}
                    <li><a href="#" class="nav_link" id="open-login-modal"><i class="fas fa-heart"></i></a></li>
                    <!-- <li><a href="{{ route('register') }}" class="nav_link"><i class="fas fa-user-plus"></i></a></li>
                    <li><a href="{{ route('login') }}" class="nav_link"><i class="fas fa-sign-in-alt"></i></a></li> -->
                @else
                    {{-- Le lien Favoris redirige vers la page des favoris si l'utilisateur est connecté --}}
                    <!-- <li><a href="{{ route('favorites.index') }}" class="nav_link"><i class="fas fa-heart"></i></a></li>
                    <li><a href="{{ route('dashboard') }}" class="nav_link"><i class="fas fa-user-circle"></i></a></li> -->
                    <li><a href="#" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                    </a></li>
                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
            <button class="button" id="form-open">Assistant</button>
            <button class="menu_toggle_btn" id="menu-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
        </nav>
    </header>

    <main class="apartment-main-content">
        <div class="container">
            <section class="apartment-header-section">
                <h1>{{ $residence->nom ?? 'Détails de l\'Appartement' }}</h1>
                <div class="apartment-meta">
                    <span class="location"><i class="fas fa-map-marker-alt"></i> {{ $residence->quartier ?? 'Quartier' }}, {{ $residence->ville ?? 'Ville' }}, {{ $residence->pays ?? 'Pays' }}</span>
                    @php
                        $avgRating = $residence->reviews->avg('note');
                        $reviewCount = $residence->reviews->count();
                    @endphp
                    <span class="rating">
                        @if($avgRating)
                            <i class="fas fa-star"></i> {{ number_format($avgRating, 2) }} ({{ $reviewCount }} avis)
                        @else
                            Aucun avis pour le moment
                        @endif
                    </span>
                    <div class="actions">
                        <a href="#" class="action-link"><i class="fas fa-share-alt"></i> Partager</a>
                        {{-- Le lien Enregistrer ouvre la modale de connexion si l'utilisateur n'est pas connecté --}}
                        @guest
                            <a href="#" class="action-link open-login-modal-trigger"><i class="fas fa-heart"></i> Enregistrer</a>
                        @else
                            <a href="#" class="action-link"><i class="fas fa-heart"></i> Enregistrer</a> {{-- Ou une logique pour ajouter aux favoris --}}
                        @endguest
                    </div>
                </div>
            </section>

            <section class="apartment-gallery">
                @php
                    // Assurez-vous que $residence->images n'est pas null avant d'appeler des méthodes dessus
                    $allImages = $residence->images ?? collect(); // Si null, utilise une collection vide
                    $mainImage = $allImages->where('est_principale', true)->first() ?? $allImages->first();
                    $thumbnailImages = $allImages->filter(function($image) use ($mainImage) {
                        return $image->id !== ($mainImage->id ?? null);
                    })->take(4); // Appelle take() sur une collection (même vide)
                @endphp
                <div class="main-image">
                    <img src="{{ $mainImage ? asset($mainImage->chemin_image) : 'https://placehold.co/800x600/C0C0C0/333333?text=Image+principale' }}" alt="Image principale de l'appartement">
                </div>
                <div class="thumbnail-grid">
                    @forelse($thumbnailImages as $thumb)
                        <img src="{{ asset($thumb->chemin_image) }}" alt="Miniature de {{ $residence->nom }}">
                    @empty
                        {{-- Placeholders si pas assez de miniatures --}}
                        <img src="https://placehold.co/500x300/C0C0C0/333333?text=Image+1" alt="Miniature 1">
                        <img src="https://placehold.co/500x300/C0C0C0/333333?text=Image+2" alt="Miniature 2">
                        <img src="https://placehold.co/500x300/C0C0C0/333333?text=Image+3" alt="Miniature 3">
                        <img src="https://placehold.co/500x300/C0C0C0/333333?text=Image+4" alt="Miniature 4">
                    @endforelse
                </div>
            </section>

            <section class="apartment-details-layout">
                <div class="details-left-column">
                    <div class="overview-section">
                        <h2>Appartement entier : Hôte {{ $residence->user?->name ?? 'Inconnu' }}</h2>
                        @php
                            // Assurez-vous que $residence->types n'est pas null avant d'appeler first() ou max()
                            $allTypes = $residence->types ?? collect(); // Si null, utilise une collection vide
                            $firstType = $allTypes->first();
                            $numGuests = $firstType->nb_voyageurs ?? 'N/A';
                            $numBedrooms = $firstType->nb_chambres ?? 'N/A';
                            $numBeds = $firstType->nombre_lits ?? 'N/A';
                            $numBathrooms = $firstType->nb_salles_de_bain ?? 'N/A';
                        @endphp
                        <p class="guest-info">
                            <i class="fas fa-users"></i> {{ $numGuests }} voyageur{{ $numGuests > 1 ? 's' : '' }}
                            &bull; <i class="fas fa-bed"></i> {{ $numBedrooms }} chambre{{ $numBedrooms > 1 ? 's' : '' }}
                            &bull; <i class="fas fa-bed"></i> {{ $numBeds }} lit{{ $numBeds > 1 ? 's' : '' }}
                            &bull; <i class="fas fa-shower"></i> {{ $numBathrooms }} salle{{ $numBathrooms > 1 ? 's' : '' }} de bain
                        </p>
                        <div class="host-summary">
                            <img src="{{ $residence->user?->profile_picture ? asset($residence->user->profile_picture) : 'https://placehold.co/50x50/B0B0B0/FFFFFF?text=H' }}" alt="Photo de l'hôte" class="host-profile_picture">
                            <p>Hôte : {{ $residence->user?->name ?? 'Inconnu' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="description-section">
                        <h3>Description</h3>
                        <p>{{ $residence->description ?? 'Aucune description disponible.' }}</p>
                        <a href="#" class="read-more">En savoir plus <i class="fas fa-chevron-right"></i></a>
                    </div>

                    <hr>

                    <div class="Equipment-section">
                        <h3>Ce que ce logement offre</h3>
                        <ul class="Equipment-list">
                            {{-- Assurez-vous que $residence->Equipment n'est pas null --}}
                            @forelse(($residence->Equipment ?? collect())->take(8) as $amenity) {{-- Afficher les 8 premiers --}}
                                @php
                                    $icon = 'fas fa-check-circle'; // Icône par défaut
                                    switch(Str::slug($amenity->nom)) {
                                        case 'wifi': $icon = 'fas fa-wifi'; break;
                                        case 'climatisation': $icon = 'fas fa-snowflake'; break;
                                        case 'television': $icon = 'fas fa-tv'; break;
                                        case 'cuisine-equipee': $icon = 'fas fa-kitchen-set'; break;
                                        case 'parking-gratuit-sur-place': $icon = 'fas fa-parking'; break;
                                        case 'piscine': $icon = 'fas fa-swimming-pool'; break;
                                        case 'lave-linge': $icon = 'fas fa-washer'; break;
                                        case 'jacuzzi': $icon = 'fas fa-hot-tub'; break;
                                        case 'eau-chaude': $icon = 'fas fa-water'; break;
                                        case 'toilettes': $icon = 'fas fa-toilet'; break;
                                        case 'savon': $icon = 'fas fa-soap'; break;
                                        case 'draps-de-lit': $icon = 'fas fa-bed'; break;
                                        case 'seche-linge': $icon = 'fas fa-dryer'; break;
                                        case 'lit-bebe': $icon = 'fas fa-baby-carriage'; break;
                                        case 'chaise-haute': $icon = 'fas fa-child'; break;
                                        case 'ventilateur-de-plafond': $icon = 'fas fa-fan'; break;
                                        case 'extincteur': $icon = 'fas fa-fire-extinguisher'; break;
                                        case 'trousse-de-premiers-secours': $icon = 'fas fa-first-aid'; break;
                                        case 'espace-de-travail-dedie': $icon = 'fas fa-laptop'; break;
                                        case 'ethernet': $icon = 'fas fa-ethernet'; break;
                                        case 'cafetiere': $icon = 'fas fa-mug-hot'; break;
                                        case 'vaisselle-et-couverts': $icon = 'fas fa-utensils'; break;
                                        case 'ascenseur': $icon = 'fas fa-elevator'; break;
                                        case 'service-de-conciergerie': $icon = 'fas fa-concierge-bell'; break;
                                        case 'service-en-chambre': $icon = 'fas fa-bell'; break;
                                        // Ajoutez d'autres cas si nécessaire
                                    }
                                @endphp
                                <li><i class="{{ $icon }}"></i> {{ $amenity->nom }}</li>
                            @empty
                                <li>Aucun équipement listé.</li>
                            @endforelse
                        </ul>
                        @if(($residence->Equipment ?? collect())->count() > 8) {{-- Vérification null-safe ici aussi --}}
                            <button class="show-all-Equipment" id="show-all-Equipment-btn">Afficher les {{ ($residence->Equipment ?? collect())->count() }} équipements</button>
                        @endif
                    </div>

                    <hr>

                    <div class="reviews-section">
                        @if($avgRating)
                            <h3><i class="fas fa-star"></i> {{ number_format($avgRating, 2) }} &bull; {{ $reviewCount }} avis</h3>
                        @else
                            <h3>Aucun avis pour le moment</h3>
                        @endif
                        <div class="review-grid">
                            {{-- Assurez-vous que $residence->reviews n'est pas null --}}
                            @forelse(($residence->reviews ?? collect())->take(2) as $review) {{-- Afficher les 2 premiers avis --}}
                                <div class="review-item">
                                    <div class="reviewer-info">
                                        <img src="{{ $review->user?->profile_picture ? asset($review->user->profile_picture) : 'https://placehold.co/40x40/D0D0D0/FFFFFF?text=U' }}" alt="profile_picture utilisateur" class="reviewer-profile_picture">
                                        <span>{{ $review->user?->name ?? 'Anonyme' }}</span>
                                    </div>
                                    <p class="review-comment">"{{ $review->commentaire ?? 'Pas de commentaire.' }}"</p>
                                    <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('F Y') }}</span>
                                </div>
                            @empty
                                <p>Soyez le premier à laisser un avis !</p>
                            @endforelse
                        </div>
                        @if($reviewCount > 2)
                            <button class="show-all-reviews" id="show-all-reviews-btn">Afficher les {{ $reviewCount }} avis</button>
                        @endif
                    </div>

                    <hr>

                    <div class="location-map-section">
                        <h3>Faite votre choix</h3>
                        <p>{{ $residence->quartier ?? 'Quartier' }}, {{ $residence->ville ?? 'Ville' }}, {{ $residence->pays ?? 'Pays' }}</p>
                        <div class="map-placeholder">
                            <img src="https://placehold.co/600x400/F0F0F0/333333?text=Carte+de+la+localisation" alt="Carte de la localisation">
                        </div>
                    </div>

                </div>

                <div class="details-right-column">
                    <div class="booking-card">
                        <div class="booking-header">
                            @php
                                // Assurez-vous que $residence->types n'est pas null avant d'appeler min() ou max()
                                $allTypes = $residence->types ?? collect();
                                $minPrice = $allTypes->min('prix_base');
                            @endphp
                            <span class="price">{{ number_format($minPrice ?? 0, 0, ',', ' ') }} FCFA</span> <span class="per-night">par nuit</span>
                            <span class="rating">
                                @if($avgRating)
                                    <i class="fas fa-star"></i> {{ number_format($avgRating, 2) }} &bull; {{ $reviewCount }} avis
                                @else
                                    Aucun avis
                                @endif
                            </span>
                        </div>
                        <form class="booking-form" action="#" method="POST"> {{-- Ajouter la méthode et l'action réelle --}}
                            @csrf {{-- Protection CSRF si la form est soumise --}}
                            <div class="form-group date-selection">
                                <div class="date-input-group">
                                    <label for="check_in_date">ARRIVÉE</label>
                                    <input type="date" id="check_in_date" name="check_in_date">
                                </div>
                                <div class="date-input-group">
                                    <label for="check_out_date">DÉPART</label>
                                    <input type="date" id="check_out_date" name="check_out_date">
                                </div>
                            </div>
                            <div class="form-group guests-selection">
                                <label for="num_guests">PERSONNES</label>
                                <select id="num_guests" name="num_guests">
                                    @php
                                        $maxGuests = $allTypes->max('nb_voyageurs') ?? 1; // Utilise $allTypes
                                    @endphp
                                    @for($i = 1; $i <= $maxGuests; $i++)
                                        <option value="{{ $i }}">{{ $i }} Personne(s){{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="check-availability-btn">Vérifier les disponibilités</button>
                        </form>
                        <div class="price-breakdown">
                            {{-- Ces calculs nécessitent du JavaScript pour être dynamiques --}}
                            <p><span>{{ number_format($minPrice ?? 0, 0, ',', ' ') }} FCFA x 5 nuits</span> <span>{{ number_format(($minPrice ?? 0) * 5, 0, ',', ' ') }} FCFA</span></p>
                            <p><span>Frais de service</span> <span>10,000 FCFA</span></p>
                            <p class="total-price"><span>Total</span> <span>{{ number_format((($minPrice ?? 0) * 5) + 10000, 0, ',', ' ') }} FCFA</span></p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Modale pour afficher tous les équipements --}}
        <div class="Equipment-modal-overlay" id="Equipment-modal-overlay">
            <div class="Equipment-modal-content">
                <button class="Equipment-modal-close" id="Equipment-modal-close">
                    <i class="fas fa-times"></i>
                </button>
                <h2>Ce que propose ce logement</h2>

                <div class="Equipment-modal-grid">
                    {{-- Assurez-vous que $residence->Equipment n'est pas null --}}
                    @forelse(($residence->Equipment ?? collect()) as $amenity)
                        @php
                            $icon = 'fas fa-check-circle'; // Icône par défaut
                            switch(Str::slug($amenity->nom)) {
                                case 'wifi': $icon = 'fas fa-wifi'; break;
                                case 'climatisation': $icon = 'fas fa-snowflake'; break;
                                case 'television': $icon = 'fas fa-tv'; break;
                                case 'cuisine-equipee': $icon = 'fas fa-kitchen-set'; break;
                                case 'parking-gratuit-sur-place': $icon = 'fas fa-parking'; break;
                                case 'piscine': $icon = 'fas fa-swimming-pool'; break;
                                case 'lave-linge': $icon = 'fas fa-washer'; break;
                                case 'jacuzzi': $icon = 'fas fa-hot-tub'; break;
                                case 'eau-chaude': $icon = 'fas fa-water'; break;
                                case 'toilettes': $icon = 'fas fa-toilet'; break;
                                case 'savon': $icon = 'fas fa-soap'; break;
                                case 'draps-de-lit': $icon = 'fas fa-bed'; break;
                                case 'seche-linge': $icon = 'fas fa-dryer'; break;
                                case 'lit-bebe': $icon = 'fas fa-baby-carriage'; break;
                                case 'chaise-haute': $icon = 'fas fa-child'; break;
                                case 'ventilateur-de-plafond': $icon = 'fas fa-fan'; break;
                                case 'extincteur': $icon = 'fas fa-fire-extinguisher'; break;
                                case 'trousse-de-premiers-secours': $icon = 'fas fa-first-aid'; break;
                                case 'espace-de-travail-dedie': $icon = 'fas fa-laptop'; break;
                                case 'ethernet': $icon = 'fas fa-ethernet'; break;
                                case 'cafetiere': $icon = 'fas fa-mug-hot'; break;
                                case 'vaisselle-et-couverts': $icon = 'fas fa-utensils'; break;
                                case 'ascenseur': $icon = 'fas fa-elevator'; break;
                                case 'service-de-conciergerie': $icon = 'fas fa-concierge-bell'; break;
                                case 'service-en-chambre': $icon = 'fas fa-bell'; break;
                                // Ajoutez d'autres cas si nécessaire
                            }
                        @endphp
                        <div class="Equipment-modal-section">
                            <ul>
                                <li><i class="{{ $icon }}"></i> {{ $amenity->nom }}</li>
                            </ul>
                        </div>
                    @empty
                        <p class="col-span-full">Aucun équipement disponible.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Résidences Nehemie. Tous droits réservés.</p>
    </footer>
    

    {{-- Modale de Connexion/Inscription --}}
    <div class="login-modal-overlay" id="login-modal-overlay">
        <div class="login-modal" id="login-modal">
            <div class="login-modal-header">
                <i class="fas fa-times login-modal-close-btn" id="login-modal-close-btn"></i>
                <h3 class="login-modal-title">Connexion ou inscription</h3>
            </div>
            <div class="login-modal-content">
                <h2 class="login-modal-welcome">Bienvenue sur Résidences Nehemie</h2>
                <form class="login-form">
                    <div class="form-group">
                        <label for="country-region">Pays/région</label>
                        <select id="country-region" name="country-region">
                            <option value="ci">Côte d'Ivoire (+225)</option>
                            <option value="fr">France (+33)</option>
                            <option value="us">États-Unis (+1)</option>
                            <option value="us">Autre</option>
                            {{-- Ajoutez d'autres pays si nécessaire --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone-number">Numéro de téléphone</label>
                        <input type="tel" id="phone-number" name="phone-number" placeholder="Numéro de téléphone">
                        <p class="info-text">Nous vous appellerons ou vous enverrons un SMS pour confirmer votre numéro. Les frais standards d'envoi de messages et d'échange de données s'appliquent. <a href="#">Politique de confidentialité</a></p>
                    </div>
                    <button type="submit" class="button login-continue-btn">Continuer</button>
                </form>
                <div class="or-separator"><span>ou</span></div>
                <button class="social-login-btn google-btn">
                    <i class="fab fa-google"></i> Continuer avec Google
                </button>
                <button class="social-login-btn apple-btn">
                    <i class="fab fa-apple"></i> Continuer avec Apple
                </button>
                <button class="social-login-btn email-btn">
                    <i class="fas fa-envelope"></i> Continuer avec une adresse e-mail
                </button>
                <button class="social-login-btn facebook-btn">
                    <i class="fab fa-facebook-f"></i> Continuer avec Facebook
                </button>
            </div>
        </div>
    </div>
    {{-- Modale de l'Assistant de Discussion (Ajoutée pour cohérence si nécessaire, sinon supprimez) --}}
    <div class="chat_container" id="chat-container">
        <i class="fas fa-times chat_close" id="chat-close-btn"></i>
        <div class="chat_header">
            <h2>Assistant Virtuel</h2>
        </div>
        <div class="chat_messages" id="chat-messages">
            <div class="message bot-message">Bonjour ! Comment puis-je vous aider aujourd'hui ?</div>
        </div>
        <div class="chat_input_area">
            <input type="text" id="chat_input" placeholder="Tapez votre message ici..." />
            <button id="send_chat_btn" class="button">Envoyer</button>
        </div>
    </div>

    {{-- Nouvelle Sidebar de Contact (à droite) --}}
    <div class="contactsidebar" id="contactsidebar">
        <div class="contactsidebar_header">
            <h3>Contactez-nous</h3>
            <i class="fas fa-times contactsidebar_close_btn" id="contactsidebar_close_btn"></i>
        </div>
        <div class="contactsidebar_content">
            <p>Où que vous soyez, nos conseillers seront ravis de vous aider.</p>
            <ul>
                <li><i class="fas fa-phone-alt"></i> +225 00 00 00 00</li>
                <li><i class="fas fa-comments"></i> Discuter avec un conseiller (Disponible)</li>
                <li><i class="fas fa-envelope"></i> Envoyer un e-mail</li>
                <li><i class="fab fa-whatsapp"></i> WhatsApp</li>
                <li><i class="fab fa-facebook-messenger"></i> Facebook Messenger</li>
                <li><i class="fas fa-deaf"></i> Service Clients Sourds et Malentendants</li>
            </ul>
            <p class="help-text">Besoin d'aide ?</p>
            <button class="button contactsidebar_help_btn">FAQ</button>
        </div>
    </div>

    {{-- Overlay partagé pour les modales et sidebars --}}
    <div class="overlay" id="overlay"></div>
</body>
</html>
