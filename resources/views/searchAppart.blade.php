@extends('layouts.myapp')

@section('content')
<section class="search-results">
    <h2 class="section-title">Résultats de votre recherche</h2>
    <p class="section-description">
        Voici les appartements disponibles pour vos dates sélectionnées.
    </p>

    {{--Formulaire de recherche sticky--}}
    <div class="search-bar-wrapper">
        <form class="search-bar" action="{{ route('residences.search') }}" method="POST">
            @csrf
            <div class="search-bar-container">
                {{-- Destination --}}
                <div class="search-bar-item">
                    <label for="destination"></label>
                    <input type="text" id="destination" name="destination" placeholder="Rechercher une destination"
                        value="{{ request('destination') }}">
                </div>

                {{-- Arrivée --}}
                <div class="search-bar-item">
                    <label for="check_in_date">Arrivée</label>
                    <input type="text" id="check_in_date" name="date_arrivee" readonly placeholder="Quand ?"
                        value="{{ request('date_arrivee') }}">
                </div>

                {{-- Départ --}}
                <div class="search-bar-item">
                    <label for="check_out_date">Départ</label>
                    <input type="text" id="check_out_date" name="date_depart" readonly placeholder="Quand ?"
                        value="{{ request('date_depart') }}">
                </div>

                {{-- Voyageurs --}}
                <div class="search-bar-item voyageurs-dropdown">
                    <label for="voyageurs">Personnes</label>
                    <input type="text" id="voyageurs" name="voyageurs" readonly
                        placeholder="nombre de personnes" value="{{ request('personnes') }}">
                    {{-- Pop voyageurs --}}
                    <div class="voyageurs-pop">
                        <div class="voyageur-row">
                            <span>Adultes <small>13 ans et plus</small></span>
                            <div class="counter">
                                <button type="button" class="minus">-</button>
                                <span class="count" data-type="adultes">0</span>
                                <button type="button" class="plus">+</button>
                            </div>
                        </div>
                        <div class="voyageur-row">
                            <span>Enfants <small>2 à 12 ans</small></span>
                            <div class="counter">
                                <button type="button" class="minus">-</button>
                                <span class="count" data-type="enfants">0</span>
                                <button type="button" class="plus">+</button>
                            </div>
                        </div>
                        <div class="voyageur-row">
                            <span>Bébés <small>- 2 ans</small></span>
                            <div class="counter">
                                <button type="button" class="minus">-</button>
                                <span class="count" data-type="bebes">0</span>
                                <button type="button" class="plus">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bouton recherche --}}
                <div class="search-bar-button">
                    <button type="submit">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Résultats --}}
    @if($residences->isEmpty())
    <div class="no-results">
        <p>Aucun appartement disponible pour les dates choisies.</p>
        <a href="{{ url('/') }}" class="btn-back-home">🔙 Retour à l’accueil</a>
    </div>
    @else
    <div class="properties-grid">
        @foreach($residences as $residence)
        <a href="{{ route('residences.detailsAppart', $residence->id) }}" class="property-card-link">
            <div class="property-card">
                <div class="property-image">
                    @php
                    $featuredImage = $residence->images->where('est_principale', true)->first()
                    ?? $residence->images->sortBy('order')->first();
                    $imageSrc = $featuredImage ? asset($featuredImage->chemin_image)
                    : 'https://placehold.co/400x300/C0C0C0/333333?text=Appartement';
                    @endphp
                    <img src="{{ $imageSrc }}" alt="{{ $residence->nom }}">
                </div>

                <div class="property-details">
                    <h3>{{ Str::limit($residence->nom, 40) }}</h3>
                    <p class="property-location">{{ $residence->ville }}</p>
                    <p class="property-price">
                        À partir de
                        <strong>{{ number_format($residence->types->min('prix_base') ?? 0, 0, ',', ' ') }}</strong>
                        XOF / nuit
                    </p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</section>

<style>
    /* Sticky search bar */
    .search-bar-wrapper {
        position: sticky;
        top: 70px;
        /* hauteur du header */
        z-index: 1000;
        background: #fff;
        padding: 0.5rem 0;
    }

    .search-bar {
        display: flex;
        justify-content: center;
    }

    .search-bar-container {
        display: flex;
        align-items: center;
        background: #f7f7f7;
        border-radius: 50px;
        padding: 0.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .search-bar-item {
        padding: 0.5rem 1rem;
        border-right: 1px solid #ddd;
        min-width: 160px;
    }

    .search-bar-item:last-of-type {
        border-right: none;
    }

    .search-bar-item input {
        border: none;
        background: transparent;
        outline: none;
    }

    .search-bar-button button {
        background: #ff385c;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 0.8rem 1.5rem;
        font-weight: bold;
        cursor: pointer;
    }

    /* Pop Voyageurs */
    .voyageurs-dropdown {
        position: relative;
        cursor: pointer;
    }

    .voyageurs-pop {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 280px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        margin-top: 0.5rem;
        z-index: 2000;
    }

    .voyageur-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
    }

    .voyageur-row:last-child {
        border-bottom: none;
    }

    .counter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .counter button {
        border: 1px solid #ccc;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        background: none;
        cursor: pointer;
    }

    .counter span {
        min-width: 20px;
        text-align: center;
    }

    /* Show pop on focus */
    .voyageurs-dropdown:focus-within .voyageurs-pop,
    .voyageurs-dropdown:hover .voyageurs-pop {
        display: block;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll(".counter");
        counters.forEach(counter => {
            const minus = counter.querySelector(".minus");
            const plus = counter.querySelector(".plus");
            const span = counter.querySelector(".count");

            plus.addEventListener("click", () => {
                span.textContent = parseInt(span.textContent) + 1;
                updateInput();
            });

            minus.addEventListener("click", () => {
                if (parseInt(span.textContent) > 0) {
                    span.textContent = parseInt(span.textContent) - 1;
                    updateInput();
                }
            });
        });

        function updateInput() {
            let total = 0;
            document.querySelectorAll(".count").forEach(c => total += parseInt(c.textContent));
            document.getElementById("voyageurs").value = total > 0 ? total + " voyageurs" : "";
        }
    });
</script>
@endsection