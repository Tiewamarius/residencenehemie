@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie')

@section('content')

@php
$allImages = $residence->images ?? collect();
$mainImage = $allImages->where('est_principale', true)->first() ?? $allImages->first();
$thumbnailImages = $allImages->filter(fn($image) => $image->id !== ($mainImage->id ?? null))->take(4);

$allTypes = $residence->types ?? collect();
$firstType = $allTypes->first();
$minPrice = $allTypes->min('prix_base');
$maxGuests = $allTypes->max('capacite_adultes') ?? 1;
$maxEnfants = $allTypes->max('capacite_enfants') ?? 0;

$reviews = $residence->reviews ?? collect();
$avgRating = $reviews->avg('note');
$reviewCount = $reviews->count();

$amenityIcons = [
'wifi' => 'fas fa-wifi',
'climatisation' => 'fas fa-snowflake',
'television' => 'fas fa-tv',
'cuisine-equipee' => 'fas fa-kitchen-set',
'parking-gratuit-sur-place' => 'fas fa-parking',
'piscine' => 'fas fa-swimming-pool',
'lave-linge' => 'fas fa-washer',
'jacuzzi' => 'fas fa-hot-tub',
'eau-chaude' => 'fas fa-water',
'toilettes' => 'fas fa-toilet',
'savon' => 'fas fa-soap',
'draps-de-lit' => 'fas fa-bed',
'seche-linge' => 'fas fa-dryer',
'lit-bebe' => 'fas fa-baby-carriage',
'chaise-haute' => 'fas fa-child',
'ventilateur-de-plafond' => 'fas fa-fan',
'extincteur' => 'fas fa-fire-extinguisher',
'trousse-de-premiers-secours' => 'fas fa-first-aid',
'espace-de-travail-dedie' => 'fas fa-laptop',
'ethernet' => 'fas fa-ethernet',
'cafetiere' => 'fas fa-mug-hot',
'vaisselle-et-couverts' => 'fas fa-utensils',
'ascenseur' => 'fas fa-elevator',
'service-de-conciergerie' => 'fas fa-concierge-bell',
'service-en-chambre' => 'fas fa-bell',
];
@endphp

<main class="apartment-main-content">
    <div class="container">
        <section class="apartment-header-section">
            <h1>{{ $residence->nom ?? 'Détails de l\'Appartement' }}</h1>
            <div class="apartment-meta">
                <span class="location"><i class="fas fa-map-marker-alt"></i> {{ $residence->quartier ?? 'Quartier' }}, {{ $residence->ville ?? 'Ville' }}, {{ $residence->pays ?? 'Pays' }}</span>
                <span class="rating">
                    @if($avgRating)
                    <i class="fas fa-star"></i> {{ number_format($avgRating, 2) }} ({{ $reviewCount }} avis)
                    @else
                    Aucun avis pour le moment
                    @endif
                </span>
                <div class="actions">
                    <a href="#" class="action-link"><i class="fas fa-share-alt"></i> Partager</a>
                    @guest
                    <a href="#" class="action-link open-login-modal-trigger"><i class="fas fa-heart"></i> Enregistrer</a>
                    @else
                    <a href="#" class="action-link"><i class="fas fa-heart"></i> Enregistrer</a>
                    @endguest
                </div>
            </div>
        </section>

        <section class="apartment-gallery">
            <div class="main-image">
                <img src="{{ $mainImage ? asset($mainImage->chemin_image) : 'https://placehold.co/800x600/C0C0C0/333333?text=Image+principale' }}" alt="Image principale de l'appartement">
            </div>
            <div class="thumbnail-grid">
                @forelse($thumbnailImages as $thumb)
                <img src="{{ asset($thumb->chemin_image) }}" alt="Miniature de {{ $residence->nom }}">
                @empty
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
                    <p class="guest-info">
                        <i class="fas fa-users"></i> {{ $firstType->nb_voyageurs ?? 'N/A' }} Personne{{ ($firstType->nb_voyageurs ?? 0) > 1 ? 's' : '' }}
                        &bull; <i class="fas fa-bed"></i> {{ $firstType->nb_chambres ?? 'N/A' }} chambre{{ ($firstType->nb_chambres ?? 0) > 1 ? 's' : '' }}
                        &bull; <i class="fas fa-bed"></i> {{ $firstType->nombre_lits ?? 'N/A' }} lit{{ ($firstType->nombre_lits ?? 0) > 1 ? 's' : '' }}
                        &bull; <i class="fas fa-shower"></i> {{ $firstType->nb_salles_de_bain ?? 'N/A' }} salle{{ ($firstType->nb_salles_de_bain ?? 0) > 1 ? 's' : '' }} de bain
                    </p>
                    <div class="host-summary">
                        <img src="{{ $residence->user?->profile_picture ? asset($residence->user->profile_picture) : 'https://placehold.co/50x50/B0B0B0/FFFFFF?text=H' }}" alt="Photo de l'hôte" class="host-profile-picture">
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

                <div class="equipment-section">
                    <h3>Ce que ce logement offre</h3>
                    <ul class="equipment-list">
                        @forelse(($residence->Equipment ?? collect())->take(8) as $amenity)
                        @php
                        $icon = $amenityIcons[Str::slug($amenity->nom)] ?? 'fas fa-check-circle';
                        @endphp
                        <li><i class="{{ $icon }}"></i> {{ $amenity->nom }}</li>
                        @empty
                        <li>Aucun équipement listé.</li>
                        @endforelse
                    </ul>
                    @if(($residence->Equipment ?? collect())->count() > 8)
                    <button class="show-all-equipment" id="show-all-equipment-btn">Afficher les {{ $residence->Equipment->count() }} équipements</button>
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
                        @forelse(($residence->reviews ?? collect())->take(2) as $review)
                        <div class="review-item">
                            <div class="reviewer-info">
                                <img src="{{ $review->user?->profile_picture ? asset($review->user->profile_picture) : 'https://placehold.co/40x40/D0D0D0/FFFFFF?text=U' }}" alt="profile_picture utilisateur" class="reviewer-profile-picture">
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
                        <span class="price">{{ number_format($minPrice ?? 0, 0, ',', ' ') }} FCFA</span> <span class="per-night">/jour</span>
                        <span class="rating">
                            @if($avgRating)
                            <i class="fas fa-star"></i> {{ number_format($avgRating, 2) }} &bull; {{ $reviewCount }} avis
                            @else
                            Aucun avis
                            @endif
                        </span>
                    </div>

                    {{-- Formulaire pour les utilisateurs connectés --}}
                    @auth
                    <form class="booking-form" action="{{ route('residences.reserver', $residence->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="residence_id" value="{{ $residence->id }}">
                        <input type="hidden" name="total_price" id="total-price-input">
                        <input type="hidden" name="type_id" value="{{ $firstType->id ?? '' }}">

                        <div class="form-group date-selection">
                            <div class="date-input-group">
                                <label for="check_in_date_auth">ARRIVÉE</label>
                                <input type="text" id="check_in_date_auth" name="date_arrivee" placeholder="Ajouter une date" readonly>
                            </div>
                            <div class="date-input-group">
                                <label for="check_out_date_auth">DÉPART</label>
                                <input type="text" id="check_out_date_auth" name="date_depart" placeholder="Ajouter une date" readonly>
                            </div>
                        </div>

                        <div class="form-group guests-selection">
                            <label for="adults_auth">PERSONNES ADULTES</label>
                            <select id="adults_auth" name="nombre_adultes" required>
                                @for($i = 1; $i <= $maxGuests; $i++)
                                    <option value="{{ $i }}">{{ $i }} Adulte(s)</option>
                                    @endfor
                            </select>
                        </div>

                        <div class="form-group guests-selection">
                            <label for="children_auth">PERSONNES ENFANTS</label>
                            <select id="children_auth" name="nombre_enfants" required>
                                @for($i = 0; $i <= $maxEnfants; $i++)
                                    <option value="{{ $i }}">{{ $i }} Enfant(s)</option>
                                    @endfor
                            </select>
                        </div>

                        <button type="submit" class="check-availability-btn" disabled>RÉSERVER</button>
                    </form>
                    @endauth

                    {{-- Formulaire pour les invités (non connectés) --}}
                    @guest
                    <form class="booking-form" action="{{ route('reservations.guest.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="residence_id" value="{{ $residence->id }}">
                        <input type="hidden" name="total_price" id="total-price-input">
                        <input type="hidden" name="type_id" value="{{ $firstType->id ?? '' }}">

                        <div class="form-group date-selection">
                            <div class="date-input-group">
                                <label for="check_in_date_guest">ARRIVÉE</label>
                                <input type="text" id="check_in_date_guest" name="date_arrivee" placeholder="Ajouter une date" readonly>
                            </div>
                            <div class="date-input-group">
                                <label for="check_out_date_guest">DÉPART</label>
                                <input type="text" id="check_out_date_guest" name="date_depart" placeholder="Ajouter une date" readonly>
                            </div>
                        </div>

                        <div class="form-group guests-selection">
                            <label for="adults_guest">PERSONNES ADULTES</label>
                            <select id="adults_guest" name="nombre_adultes" required>
                                @for($i = 1; $i <= $maxGuests; $i++)
                                    <option value="{{ $i }}">{{ $i }} Adulte(s)</option>
                                    @endfor
                            </select>
                        </div>

                        <div class="form-group guests-selection">
                            <label for="children_guest">PERSONNES ENFANTS</label>
                            <select id="children_guest" name="nombre_enfants" required>
                                @for($i = 0; $i <= $maxEnfants; $i++)
                                    <option value="{{ $i }}">{{ $i }} Enfant(s)</option>
                                    @endfor
                            </select>
                        </div>

                        <button type="submit" class="check-availability-btn" disabled>RÉSERVER</button>
                    </form>
                    @endguest

                    <div class="price-breakdown">
                        <p>
                            <span>{{ number_format($minPrice ?? 0, 0, ',', ' ') }} FCFA x <span id="nights-display-breakdown">0</span> nuit(s)</span>
                            <span><span id="price-subtotal">0</span> FCFA</span>
                        </p>
                        <p><span>Frais de service</span> <span>10 000 FCFA</span></p>
                        <p class="total-price"><span>Total</span> <span><span id="price-total">0</span> FCFA</span></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

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
                    @endphp
                    <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}" onerror="this.onerror=null;this.src='https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';">
                    <span class="wishlist-icon @guest open-login-modal-trigger @endguest"><i class="fas fa-heart"></i></span>
                </div>
                <div class="property-details">
                    @php $featuredAvgRating = $featuredResidence->reviews->avg('note'); @endphp
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

{{-- Injecte les périodes réservées pour Flatpickr --}}
<script>
    window.bookedDateRanges = @json($bookedDateRanges ?? []);
</script>

@endsection