@extends('layouts.myapp')

@section('content')
<section class="search-results">

    {{--Formulaire de recherche sticky--}}
    <div class="search-bar-wrapper">
        <form class="search-bar" action="{{ route('residences.search') }}" method="POST">
            @csrf
            <div class="search-bar-container">
                {{-- Destination --}}
                <div class="search-bar-item">
                    <a href="https://www.google.com/maps/search/?api=1&query=Abidjan,+Côte+d'Ivoire,+Bingerville+Fekesse"
                        target="_blank"
                        class="destination-link">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Abidjan-CI Bingerville-Fekesse</span>
                    </a>
                </div>

                {{-- Arrivée --}}
                <div class="search-bar-item">
                    <label for="check_in_date">Arrivée</label>
                    <input type="text" id="check_in_date" name="date_arrivee" readonly placeholder="Ajouter une date"
                        value="{{ request('date_arrivee') }}">
                </div>

                {{-- Départ --}}
                <div class="search-bar-item">
                    <label for="check_out_date">Départ</label>
                    <input type="text" id="check_out_date" name="date_depart" readonly placeholder="Ajouter une date"
                        value="{{ request('date_depart') }}">
                </div>

                {{-- Voyageurs --}}
                <!-- <div class="search-bar-item voyageurs-dropdown">
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
                </div> -->

                {{-- Bouton recherche --}}
                <div class="search-bar-button">
                    <button type="button" id="reset-btn">
                        <i class="fas fa-times-circle"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Résultats --}}
    <br><br><br><br>

    <p class="section-description">
        Voici les appartements disponibles pour vos dates sélectionnées.
    </p>
    @if($residences->isEmpty())
    <div class="no-results">
        <p>Aucun appartement disponible pour les dates choisies.</p>
        <a href="{{ url('/') }}" class="btn-back-home">🔙 Retour à l’accueil</a>
    </div>
    @else
    {{-- On ajoute une classe conditionnelle pour gérer l'affichage d'un seul résultat --}}
    <div class="properties-grid @if($residences->count() === 1) single-item-grid @endif">
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
                        XOF
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

        border: 0.5px solid #ed5257;
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
        background: #ed5257;
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

    /* Nouvelle classe pour un seul résultat */
    .single-item-grid {
        display: flex;
        /* Utilise Flexbox pour un meilleur contrôle */
        justify-content: center;
        /* Centre la carte horizontalement */
        max-width: 400px;
        /* Limite la largeur de la carte */
        margin: 0 auto;
        /* Centre le conteneur principal */
    }

    /* responsive */

    /* --- Responsive --- */
    @media (max-width: 992px) {
        .search-bar-container {
            flex-wrap: wrap;
            border-radius: 20px;
            padding: 0.5rem;
        }

        .search-bar-item {
            border-right: none;
            border-bottom: 1px solid #eee;
            min-width: 100%;
            text-align: left;
            padding: 0.8rem 0.5rem;
        }

        .search-bar-item:last-of-type {
            border-bottom: none;
        }

        .search-bar-button {
            width: 100%;
            text-align: center;
            margin-top: 0.5rem;
        }

        .search-bar-button button {
            width: 90%;
            border-radius: 12px;
        }

        .voyageurs-pop {
            width: 100%;
            left: 0;
            right: 0;
        }
    }

    @media (max-width: 576px) {
        .search-bar-wrapper {

            top: 60px;
            /* header plus petit sur mobile */
            padding: 0.3rem;
        }

        .search-bar-item label {
            font-size: 0.8rem;
        }

        .search-bar-item input {
            font-size: 0.9rem;
        }

        .search-bar-button button {
            font-size: 1rem;
            padding: 0.7rem;
        }

        .voyageurs-pop {
            font-size: 0.9rem;
            padding: 0.8rem;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        /** ==========================
         *  Gestion des voyageurs
         * ========================== */
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

        /** ==========================
         *  Auto-submit après choix de dates
         * ========================== */
        const checkIn = document.getElementById("check_in_date");
        const checkOut = document.getElementById("check_out_date");
        const form = document.querySelector(".search-bar");

        function trySubmit() {
            if (checkIn.value && checkOut.value) {
                form.submit();
            }
        }

        checkIn.addEventListener("change", trySubmit);
        checkOut.addEventListener("change", trySubmit);


        /** ==========================
         *  Bouton reset (vider champs)
         * ========================== */
        const resetBtn = document.getElementById("reset-btn");
        resetBtn.addEventListener("click", () => {
            // Vider les champs
            document.getElementById("destination").value = "";
            checkIn.value = "";
            checkOut.value = "";
            document.getElementById("voyageurs").value = "";

            // Réinitialiser les compteurs voyageurs
            document.querySelectorAll(".count").forEach(c => c.textContent = 0);

            // Option : recharger la page avec résultats initiaux
            window.location.href = "{{ url('/search-apartments') }}";
        });
    });
</script>

@endsection