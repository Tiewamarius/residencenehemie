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
            <h3>BIENVENUE À LA RÉSIDENCE NÉHÉMIE</h3>
            <p class="hero-card-description">Renseigner ci dessous votre periode de séjour</p>
            <button class="button" id="button-opens">CHERCHER</button>
            <button class="button" id="close-buttons" style="display:none;margin-bottom: 10px; background-color: gray;color:whitesmoke;">REDUIR</button>

            <form class="booking-form" action="{{ route('residences.search') }}" method="POST" id="search-form-opens" class="banner-search-form">
                @csrf
                <div class="form-group date-selection">
                    <div class="date-input-group">
                        <label for="check_in_date">ARRIVÉE</label>
                        <input type="text" id="check_in_date" name="date_arrivee" readonly placeholder="Ajouter une date">
                    </div>
                    <div class="date-input-group">
                        <label for="check_out_date">DÉPART</label>
                        <input type="text" id="check_out_date" name="date_depart" readonly placeholder="Ajouter une date">
                    </div>
                </div>


                <button type="submit" class="check-availability-btn">CHERCHER</button>

            </form>
        </div>
    </div>
</section>

{{-- Section "Nos appartements en vedette" --}}
<section class="featured-properties" id="appartements">
    <h2 class="section-title">Nos appartements en vedette</h2>
    <p class="section-description">Découvrez notre sélection des plus belles propriétés immobilières disponibles.</p>
    <div class="properties-grid">
        @forelse(($residences->take(3) ?? collect()) as $featuredResidence)
        <a href="{{ route('residences.detailsAppart', $featuredResidence->id) }}" class="property-card-link">
            <div class="property-card">
                <div class="property-image">
                    @php
                    $featuredImage = $featuredResidence->images->where('est_principale', true)->first() ?? $featuredResidence->images->sortBy('order')->first();
                    $featuredImageSource = $featuredImage ? asset($featuredImage->chemin_image) : 'https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';
                    // Check if the user has favorited this residence
                    $isFavorited = auth()->check() ? auth()->user()->favorites->contains('residence_id', $featuredResidence->id) : false;
                    @endphp
                    <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}" onerror="this.onerror=null;this.src='https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';">
                    {{-- Ajout d'attributs de données pour le JS et une classe pour l'état initial --}}
                    <span class="wishlist-icon @guest open-login-modal-trigger @endguest {{ $isFavorited ? 'active' : '' }}" data-residence-id="{{ $featuredResidence->id }}">
                        {{-- Utilisation de l'icône appropriée selon l'état --}}
                        <i class="fa-heart {{ $isFavorited ? 'fas' : 'far' }}"></i>
                    </span>
                </div>
                <div class="property-details">
                    @php
                    $featuredAvgRating = $featuredResidence->reviews->avg('note');
                    @endphp
                    <div class="property-review">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <=floor($featuredAvgRating ?? 0))
                            <i class="fas fa-star"></i>
                            @elseif ($i - floor($featuredAvgRating ?? 0) === 0.5)
                            <i class="fas fa-star-half-alt"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                            @endfor
                            <span>({{ number_format($featuredAvgRating ?? 0, 1) }}/5)</span>
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
                <img src="images/imageSecurité.jpg" alt="" style='display:none;'>
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
            <!-- Bouton pour Environnement Verdoyant -->
            <button class="feature-button"
                data-feature="Environnement_Verdoyant"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/img/residences/YQ8cnG86nw_RN2_APPART.jpg">
                <i class="fa-solid fa-tree"></i>
                <span>Environnement Verdoyant</span>
            </button>
            <!-- Bouton pour Environnement Verdoyant -->
            <button class="feature-button"
                data-feature="Qualité_de_Vie_Supérieure"
                data-image-display=" "
                data-image-section="http://127.0.0.1:8000/img/residences/YQ8cnG86nw_RN2_APPART.jpg">
                <i class="fa-solid fa-star"></i>
                <span>Qualité de Vie Supérieure</span>
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