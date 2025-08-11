@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie') {{-- Titre spécifique à cette page --}}

@section('content')
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
        <img src="{{ $imageSource }}" alt="Image de {{ $residence->nom }}" class="slider-image {{ $index === 0 ? 'active' : '' }}">
        @empty
        <img src="{{ asset('images/default_slider_1.jpg') }}" alt="Image par défaut 1" class="slider-image active">
        <img src="{{ asset('images/default_slider_2.jpg') }}" alt="Image par défaut 2" class="slider-image">
        @endforelse
    </div>

    <div class="hero-content-wrapper">
        <div class="hero-card-container">
            <h3>Bingerville: Fehkesse</h3>
            <p class="hero-card-description">Réservez des hébergements sur Résidences Néhémie</p>

            <button class="button" id="button-opens">CHERCHER</button>
            <button class="button" id="close-buttons" style="display:none;margin-bottom: 10px; background-color: gray;color:whitesmoke;">REDUIR</button>

            <form action=" #" method="GET" id="search-form-opens" class="banner-search-form">
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
                        </select>
                    </div>
                </div>
                <button type="submit" class="search-button">Rechercher</button>
            </form>
        </div>
    </div>
</section>

{{-- Section "Nos appartements en vedette" --}}
<section class="featured-properties" id="appartements">
    <h2 class="section-title">Nos appartements en vedette</h2>
    <p class="section-description">Découvrez notre sélection des plus belles propriétés immobilières disponibles.</p>
    <div class="properties-grid">
        @forelse($residences->take(3) as $featuredResidence)
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
                    <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}" onerror="this.onerror=null;this.src='https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';" alt="Cliquez">
                    <span class="wishlist-icon @guest open-login-modal-trigger @endguest"><i class="fas fa-heart"></i></span>
                </div>
                <div class="property-details">
                    <div class="property-review">
                        <p class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>(4.5/5)</span>
                        </p>
                    </div>
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

{{-- Section "Pourquoi nous choisir" --}}
<section class="why-choose-us">
    <h2 class="section-title">Pourquoi nous choisir ?</h2>
    <p class="section-description">Nous avons conçu la Résidence Néhémie pour répondre à vos attentes les plus élevées. Voici ce qui fait notre différence.</p>
    <div class="why-choose-us-content">
        <div class="feature-buttons-container">
            <!-- Bouton pour la qualité et le bien-être -->
            <button class="feature-button active"
                data-feature="quality"
                data-image-section="http://127.0.0.1:8000/img/residences/tRex3gHwJe_RN2_Appart-4.jpeg">
                <i class="fas fa-gem"></i>
                <span>Votre Bien-Être</span>
            </button>
            <!-- Bouton pour la flexibilité -->
            <button class="feature-button"
                data-feature="flexibility"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/img/residences/YQ8cnG86nw_RN2_APPART.jpg">
                <i class="fas fa-calendar-alt"></i>
                <span>La Flexibilité</span>
            </button>
            <!-- Bouton pour la sécurité -->
            <button class="feature-button"
                data-feature="security_optimal"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/images/imageSecurit%C3%A9.jpg">
                <i class="fas fa-shield-alt"></i>
                <span>Sécurité Optimale</span>
                <img src="images/imageSecurité.jpg" alt="" style='display:none;'>
            </button>
            <!-- Bouton pour les commodités -->
            <button class="feature-button"
                data-feature="amenities_comfort"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/img/residences/RwV1q1H0TW_RN8_Salon.jpg">
                <i class="fas fa-concierge-bell"></i>
                <span>Commodités & Confort</span>
            </button>
            <!-- Bouton pour l'emplacement -->
            <button class="feature-button"
                data-feature="accessibility"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/images/bckgEmplacement.jpg">
                <i class="fas fa-map-marker-alt"></i>
                <span>Emplacement idéal</span>
                <img src="images/bckgEmplacement.jpg" alt="" style='display:none;'>
            </button>
        </div>
        <div class="feature-display-area">
            <h3 id="feature-display-title">Votre Bien-Être</h3>
            <p id="feature-display-text">Nous nous engageons à offrir des propriétés de la plus haute qualité, garantissant confort et satisfaction à chaque séjour.</p>
        </div>
    </div>
</section>


{{-- Section Témoignages --}}
<section class="testimonials">
    <h2 class="section-title">Ce que nos clients disent</h2>
    <p class="section-description">Écoutez les expériences de ceux qui ont choisi Résidences Nehemie.</p>
    <div class="properties-grid">
        <div class="testimonial-card">
            <div class="flex items-center mb-4">
                <img src="images/couz.jpg" alt="Photo de profil de Jane Doe" class="rounded-full mr-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Dimitri Medvedev</h3>
                    <p class="text-sm text-gray-600">Client fidèle</p>
                </div>
            </div>
            <p class="text-gray-700">"Résidences Nehemie a dépassé toutes mes attentes. La qualité des appartements et le service client sont exceptionnels. Je recommande vivement !"</p>
        </div>
        <div class="testimonial-card">
            <div class="flex items-center mb-4">
                <img src="https://placehold.co/60x60/FF385C/FFFFFF?text=SM" alt="Photo de profil de Samuel Martin" class="rounded-full mr-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Thibaut Schoelcher</h3>
                    <p class="text-sm text-gray-600">Nouveau locataire</p>
                </div>
            </div>
            <p class="text-gray-700">"J'ai trouvé l'appartement de mes rêves en un rien de temps. Le processus a été simple et l'équipe très réactive. Un grand merci !"</p>
        </div>
        <div class="testimonial-card">
            <div class="flex items-center mb-4">
                <img src="images/bg.jpg" alt="Photo de profil d'Emma Leroy" class="rounded-full mr-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Oodo Schoelcher</h3>
                    <p class="text-sm text-gray-600">Investisseur</p>
                </div>
            </div>
            <p class="text-gray-700">"En tant qu'investisseur, la diversité et la rentabilité des propriétés proposées par Résidences Nehemie sont inégalées. Un partenaire de confiance."</p>
        </div>
    </div>
</section>
@endsection