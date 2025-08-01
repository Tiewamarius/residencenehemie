<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Résidences Nehemie</title>
    {{-- Le fichier CSS de la HomePage --}}
    <link rel="stylesheet" href="{{ asset('css/HomePage.css')}}" />

    {{-- Font Awesome (vérifiez l'intégrité si vous copiez/collez depuis un CDN) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Pour l'icône uil-times, il faut la bibliothèque 'Unicons' ou une autre. Tailwind CSS ne fournit pas cela directement.
         Si vous utilisez Unicons, ajoutez son CDN:
         <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    --}}
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="{{ route('home') }}" class="nav_logo">
                {{-- Utilisation de asset() pour le chemin de l'image du logo --}}
                {{-- L'attribut onerror est une bonne pratique pour les logos si l'image n'est pas trouvée --}}
                <img src="{{ asset('images/logo.png') }}" alt="Logo Résidences Nehemie" onerror="this.onerror=null;this.src='https://placehold.co/150x50/FFFFFF/000000?text=Votre+Logo';" style="height: 70px; width: auto;">
            </a>
            <ul class="nav_item">
                <li><a href="{{ route('HomePage') }}" class="nav_link">Accueil</a></li>
                {{-- Liens vers les résidences. Assurez-vous que la route 'residences.index' existe --}}
                <li><a href="{{ route('residences.index') }}" class="nav_link">Résidences</a></li>
                {{-- Vous devrez créer ces routes et vues Contact/A Propos si elles n'existent pas encore --}}
                <li><a href="{{ route('contact') }}" class="nav_link">Contact</a></li>
                <li><a href="{{ route('about') }}" class="nav_link">A propos</a></li>
                <li><a href="#" class="nav_link"><i class="fas fa-search"></i></a></li>
                {{-- Liens pour l'authentification. Assurez-vous que ces routes existent --}}
                @guest {{-- Affiche ces liens si l'utilisateur n'est PAS connecté --}}
                    <li><a href="{{ route('register') }}" class="nav_link"><i class="fas fa-user-plus"></i> S'inscrire</a></li>
                    <li><a href="{{ route('login') }}" class="nav_link"><i class="fas fa-sign-in-alt"></i> Se connecter</a></li>
                @else {{-- Affiche ces liens si l'utilisateur EST connecté --}}
                    <li><a href="{{ route('favorites.index') }}" class="nav_link"><i class="fas fa-heart"></i> Favoris</a></li>
                    <li><a href="{{ route('dashboard') }}" class="nav_link"><i class="fas fa-user-circle"></i> Mon Compte</a></li>
                    <li><a href="#" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
            <button class="button" id="form-open">Assistant</button>
        </nav>
    </header>

    <section class="home">
        <div class="image-slider">
            @forelse($residences->take(1) as $index => $residence)
                @php
                    $mainImage = $residence->images->where('est_principale', true)->first();
                    if (!$mainImage) {
                        $mainImage = $residence->images->sortBy('order')->first();
                    }
                    $imageSource = $mainImage ? asset($mainImage->chemin_image) : asset('images/default.jpg');
                @endphp
                <img src="{{ $imageSource }}" alt="Image de {{ $residence->nom }}" class="slider-image {{ $index === 0 ? 'active' : '' }}">
            @empty
                {{-- Fallback si aucune résidence ou image n'est trouvée pour le slider --}}
                <img src=" " alt="Image par défaut 1" class="slider-image active">
                <img src=" " alt="Image par défaut 2" class="slider-image">
                {{-- Ajoutez plus d'images par défaut si nécessaire --}}
            @endforelse
        </div>

        <div class="hero-content-wrapper">
            <div class="hero-card-container">
                {{-- Les informations ici pourraient être dynamiques ou configurables via un panneau d'admin --}}
                <h2>Bingerville: Fekesse</h2>
                <p class="hero-card-description">Trouvez et réservez des hébergements uniques sur Résidences Nehemie</p>

                {{-- Formulaire de recherche --}}
                <form action="{{ route('search.residences') }}" method="GET" class="hero-search-form">
                    <div class="form-group">
                        <label for="address">ADRESSE</label>
                        {{-- La valeur de l'adresse peut être pré-remplie ou dynamique --}}
                        <input type="text" id="address" name="address" value="Côte d'Ivoire" placeholder="Ex: Abidjan, Fekesse">
                    </div>
                    <div class="form-group date-group">
                        <div class="date-input">
                            <label for="arrivee">ARRIVÉE</label>
                            <input type="date" id="arrivee" name="arrivee" placeholder="Ajouter une date">
                        </div>
                        <div class="date-input">
                            <label for="depart">DÉPART</label>
                            <input type="date" id="depart" name="depart" placeholder="Ajouter une date">
                        </div>
                    </div>
                    {{-- Ajout de champs cachés pour le nombre de personnes si nécessaire --}}
                    <div class="form-group">
                        <label for="guests">PERSONNES</label>
                        <input type="number" id="guests" name="guests" min="1" value="1">
                    </div>
                    <button type="submit" class="search-button-hero"><i class="fas fa-search"></i> Rechercher</button>
                </form>
            </div>
        </div>

        <div class="chat_container">
            <i class="uil uil-times chat_close"></i>
            <div class="chat_header">
                <h2>Assistant Virtuel</h2>
            </div>
            <div class="chat_messages">
                <div class="message bot-message">Bonjour ! Comment puis-je vous aider aujourd'hui ?</div>
            </div>
            <div class="chat_input_area">
                <input type="text" id="chat_input" placeholder="Tapez votre message ici..." />
                <button id="send_chat_btn" class="button">Envoyer</button>
            </div>
        </div>
    </section>

    <section class="featured-properties">
        <h2 class="section-title">Nos appartements en vedette</h2>
        <p class="section-description">Découvrez notre sélection des plus belles propriétés immobilières disponibles.</p>
        <div class="properties-grid">
            @forelse($residences->take(3) as $featuredResidence) {{-- Prend les 3 premières résidences comme exemple --}}
                <div class="property-card">
                    <div class="property-image">
                        @php
                            $featuredImage = $featuredResidence->images->where('est_principale', true)->first();
                            if (!$featuredImage) {
                                $featuredImage = $featuredResidence->images->sortBy('order')->first();
                            }
                            $featuredImageSource = $featuredImage ? asset($featuredImage->chemin_image) : asset('images/default.jpg');
                        @endphp
                        <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}">
                        {{-- Icone de favoris. Vous devrez implémenter la logique d'ajout/retrait côté JS/backend --}}
                        <span class="wishlist-icon"><i class="fas fa-heart"></i></span>
                        <a href="{{ route('residences.show', $featuredResidence->id) }}" class="visit-tag">Voir plus</a>
                    </div>
                    <div class="property-details">
                        <h3>{{ Str::limit($featuredResidence->nom, 30) }}</h3> {{-- Limite le nom pour l'affichage --}}
                        <p class="property-location">{{ $featuredResidence->ville }}</p>
                        {{-- Si vous avez un prix par nuit pour la résidence ou le type le moins cher --}}
                        <p class="property-price">À partir de {{ number_format($featuredResidence->types->min('prix_base') ?? 0, 0, ',', ' ') }} XOF</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 col-span-full text-center">Aucune propriété en vedette pour le moment.</p>
            @endforelse
        </div>
    </section>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Résidences Nehemie. Tous droits réservés.</p>
    </footer>

    {{-- Script JavaScript de la HomePage --}}
    <script src="{{ asset('js/HomePage.js') }}"></script>
</body>
</html>