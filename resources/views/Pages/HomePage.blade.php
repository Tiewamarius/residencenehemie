<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Résidences Nehemie</title>
    {{-- Inclure Vite pour vos assets CSS et JS. Assurez-vous que ces fichiers existent dans resources/css et resources/js --}}
    @vite(['resources/css/HomePage.css', 'resources/js/HomePage.js'])

    {{-- Font Awesome pour les icônes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Si vous utilisez des icônes Unicons (comme uil-times), assurez-vous d'inclure leur CDN --}}
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    
</head>
<body>
    {{-- Sidebar (Menu Mobile) --}}
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
            <!-- <li><a href="" class="sidebar_nav_link"><i class="fas fa-search"></i> Recherche</a></li> -->
            {{-- Liens d'authentification pour la sidebar --}}
            @guest
                <!-- <li><a href="{{ route('register') }}" class="sidebar_nav_link"><i class="fas fa-user-plus"></i> S'inscrire</a></li> -->
                <!-- <li><a href="{{ route('login') }}" class="sidebar_nav_link"><i class="fas fa-sign-in-alt"></i> Se connecter</a></li> -->
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

    {{-- Header principal --}}
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
                    <li><a href="{{ route('favorites.index') }}" class="nav_link"><i class="fas fa-heart"></i></a></li>
                    <li><a href="{{ route('dashboard') }}" class="nav_link"><i class="fas fa-user-circle"></i></a></li>
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

    {{-- Section principale avec slider et formulaire de recherche --}}
    <section class="home">
        <div class="image-slider">
            @forelse($residences->take(8) as $index => $residence)
                @php
                    $mainImage = $residence->images->where('est_principale', true)->first();
                    if (!$mainImage) {
                        // Fallback to default slider images if no main image is found
                        $imageSource = asset('images/default_slider_' . (($index % 2) + 1) . '.jpg');
                    } else {
                        $imageSource = asset($mainImage->chemin_image);
                    }
                @endphp
                {{-- IMPORTANT: Assurez-vous que les images existent dans public/images/ ou aux chemins spécifiés par $residence->images->chemin_image --}}
                <img src="{{ $imageSource }}" alt="Image de {{ $residence->nom }}" class="slider-image {{ $index === 0 ? 'active' : '' }}">
            @empty
                {{-- Fallback si aucune résidence ou image n'est trouvée pour le slider --}}
                <img src="{{ asset('images/default_slider_1.jpg') }}" alt="Image par défaut 1" class="slider-image active">
                <img src="{{ asset('images/default_slider_2.jpg') }}" alt="Image par défaut 2" class="slider-image">
                {{-- Vous pouvez ajouter plus d'images par défaut si nécessaire dans public/images/ --}}
            @endforelse
        </div>

        <div class="hero-content-wrapper" >
            <div class="hero-card-container">
                <h3>Bingerville: Fehkesse</h3>
                <p class="hero-card-description">Trouvez et réservez des hébergements uniques sur Résidences Nehemie</p>

                <form action="#" method="GET" class="banner-search-form">
                    <div class="form-group">
                        <label for="address">ADRESSE</label>
                        <input type="text" id="address" name="address" placeholder="N'importe où">
                    </div>
                    <div class="form-group-row">
                        <div class="form-group date-input">
                            <label for="arrivee">ARRIVÉE</label>
                            <input type="date" id="arrivee" name="arrivee" placeholder="Ajouter une date">
                        </div>
                        <div class="form-group date-input">
                            <label for="depart">DÉPART</label>
                            <input type="date" id="depart" name="depart" placeholder="Ajouter une date">
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div class="form-group select-input">
                            <label for="adultes">ADULTES</label>
                            <select id="adultes" name="adultes">
                                <option value="1">1</option>
                                <option value="2" selected>2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="form-group select-input">
                            <label for="enfants">ENFANTS</label>
                            <select id="enfants" name="enfants">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="search-button">Rechercher</button>
                </form>
            </div>

            <div class="mobile-search-bar">
                <div class="mobile-search-field destination">
                    <span class="label">Destination</span>
                    <input type="text" placeholder="Rechercher une destination" name="mobile_destination">
                </div>
                <div class="mobile-search-separator"></div>
                <div class="mobile-search-field arrival">
                    <span class="label">Arrivée</span>
                    <input type="text" placeholder="Quand ?" name="mobile_arrival_date">
                </div>
                <div class="mobile-search-separator"></div>
                <div class="mobile-search-field departure">
                    <span class="label">Départ</span>
                    <input type="text" placeholder="Quand ?" name="mobile_departure_date">
                </div>
                <div class="mobile-search-separator"></div>
                <div class="mobile-search-field guests">
                    <span class="label">Voyageurs</span>
                    <input type="text" placeholder="Ajouter des..." name="mobile_guests">
                </div>
                <button class="mobile-search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        {{-- Modale de l'Assistant de Discussion --}}
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
    </section>

    {{-- Section "Nos appartements en vedette" --}}
    <section class="featured-properties">
        <h2 class="section-title">Nos appartements en vedette</h2>
        <p class="section-description">Découvrez notre sélection des plus belles propriétés immobilières disponibles.</p>
        <div class="properties-grid">
            @forelse($residences->take(3) as $featuredResidence) {{-- Affiche les 3 premières résidences comme vedettes --}}
                {{-- Rendre la carte entière cliquable en l'enveloppant dans un <a> --}}
                <a href="{{ route('residences.detailsAppart', $featuredResidence->id) }}" class="property-card-link">
                    <div class="property-card">
                        <div class="property-image">
                            @php
                                $featuredImage = $featuredResidence->images->where('est_principale', true)->first();
                                if (!$featuredImage) {
                                    $featuredImage = $featuredResidence->images->sortBy('order')->first();
                                }
                                $featuredImageSource = $featuredImage ? asset($featuredImage->chemin_image) : asset('images/default.jpg');
                            @endphp
                            <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}" alt="Cliquez">
                            {{-- L'icône de favoris ouvre la modale de connexion si l'utilisateur n'est pas connecté --}}
                            <span class="wishlist-icon @guest open-login-modal-trigger @endguest"><i class="fas fa-heart"></i></span>
                            {{-- Le bouton "Voir plus" est supprimé --}}
                        </div>
                        <div class="property-details">
                            <h3>{{ Str::limit($featuredResidence->nom, 30) }}</h3>
                            <p class="property-location">{{ $featuredResidence->ville }}</p>
                            <p class="property-price">À partir de {{ number_format($featuredResidence->types->min('prix_base') ?? 0, 0, ',', ' ') }} XOF</p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-600 col-span-full text-center">Aucune propriété en vedette pour le moment.</p>
            @endforelse
        </div>
        
    </section>

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
    {{-- Footer --}}
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

    {{-- Le script JavaScript est inclus via @vite en haut du fichier, donc pas besoin de le répéter ici --}}
</body>
</html>
