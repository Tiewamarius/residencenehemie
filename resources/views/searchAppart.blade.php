@extends('layouts.myapp')

@section('title', 'Accueil - Résidences Nehemie') {{-- Titre spécifique à cette page --}}

@section('content')

{{-- Section "Nos appartements en vedette" --}}
<section class="featured-properties" id="appartements">
    <h2 class="section-title">Nos appartements en vedette</h2>
    <p class="section-description">Découvrez notre sélection des plus belles propriétés immobilières disponibles.</p>

    {{-- 🔎 Formulaire de recherche --}}
    <div class="search-form-container">
        <form action="{{ route('residences.search') }}" method="GET" class="search-form">
            @csrf
            <div class="search-fields">
                {{-- Date arrivée --}}
                <div class="search-input">
                    <label for="date_arrivee">Arrivée</label>
                    <input type="date" id="date_arrivee" name="date_arrivee"
                        value="{{ request('date_arrivee') }}">
                </div>

                {{-- Date départ --}}
                <div class="search-input">
                    <label for="date_depart">Départ</label>
                    <input type="date" id="date_depart" name="date_depart"
                        value="{{ request('date_depart') }}">
                </div>

                {{-- Bouton recherche --}}
                <div class="search-button">
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="properties-grid">
        @forelse(($residences->take(3) ?? collect()) as $featuredResidence)
        <a href="{{ route('residences.detailsAppart', $featuredResidence->id) }}" class="property-card-link">
            <div class="property-card">
                <div class="property-image">
                    @php
                    $featuredImage = $featuredResidence->images->where('est_principale', true)->first() ?? $featuredResidence->images->sortBy('order')->first();
                    $featuredImageSource = $featuredImage ? asset($featuredImage->chemin_image) : 'https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';
                    $isFavorited = auth()->check() ? auth()->user()->favorites->contains('residence_id', $featuredResidence->id) : false;
                    @endphp
                    <img src="{{ $featuredImageSource }}" alt="{{ $featuredResidence->nom }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/400x300/C0C0C0/333333?text=Image+Appartement';">
                    <span class="wishlist-icon @guest open-login-modal-trigger @endguest {{ $isFavorited ? 'active' : '' }}"
                        data-residence-id="{{ $featuredResidence->id }}">
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

{{-- Styles rapides pour le formulaire --}}
<style>
    .search-form-container {
        margin: 20px 0 40px;
        text-align: center;
    }

    .search-form {
        display: inline-block;
        background: #fff;
        border-radius: 50px;
        padding: 10px 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .search-fields {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-input label {
        display: block;
        font-size: 12px;
        margin-bottom: 4px;
        color: #555;
    }

    .search-input input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 25px;
        min-width: 150px;
    }

    .search-button .btn-search {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 25px;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-button .btn-search:hover {
        background: #0056b3;
    }
</style>

@endsection