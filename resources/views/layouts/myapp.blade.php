<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Résidences Nehemie')</title> {{-- Titre dynamique --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Toastr CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.22.5/sweetalert2.min.js" integrity="sha512-lEAhnSkDaa0XS4yP6nlJmfBbbe2p83qBt4KlVMXy7CGe2pAtNijskiu9lLs9jB+RpGoy97P62/HngyeGPIbK2w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    {{-- Inclure vos assets CSS et JS --}}
    <!-- JS principal -->
    <script src="{{ asset('js/myapp.js') }}" defer></script>

    <!-- Homepage -->
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <script src="{{ asset('js/homepage.js') }}" defer></script>

    <!-- Détails Appartement -->
    <link rel="stylesheet" href="{{ asset('css/detailsAppart.css') }}">
    <script src="{{ asset('js/detailsAppart.js') }}" defer></script>

    <!-- Paiement -->
    <link rel="stylesheet" href="{{ asset('css/paiement.css') }}">
    <script src="{{ asset('js/paiement.js') }}" defer></script>

    <!-- Home User -->
    <link rel="stylesheet" href="{{ asset('css/homeUser.css') }}">
    <script src="{{ asset('js/homeUser.js') }}" defer></script>


    {{-- Font Awesome pour les icônes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Si vous utilisez des icônes Unicons (comme uil-times), assurez-vous d'inclure leur CDN --}}
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @yield('head_extra') {{-- Pour des styles ou scripts supplémentaires spécifiques à une page --}}
    <style>
        html {
            scroll-behavior: smooth;
            /* Pour un défilement fluide */
        }

        ul {
            list-style-type: none;
        }
    </style>
</head>

<body>
    @if(Session::has('message'))
    <script>
        swal("Message", "{{ Session::get('message') }}", 'success', {
            button: true,
            button: "Ok",
            timer: 2000
        });
    </script>
    @elseif(Session::has('message'))
    <script>
        swal("Message", "{{ Session::get('message') }}", 'error', {
            button: true,
            button: "Ok",
            timer: 2000,
            dangerMode: true,
        });
    </script>
    @endif

    {{-- Inclure la Sidebar (Menu Mobile) --}}
    @include('layouts.sidebar')

    {{-- Inclure le Header principal --}}
    @include('layouts.header')

    {{-- Contenu principal de la page --}}
    @yield('content')

    {{-- Inclure la Modale de l'Assistant de Discussion --}}
    @include('layouts.chat_assistant_modal')

    {{-- Inclure la Nouvelle Sidebar de Contact (à droite) --}}
    @include('layouts.contact_sidebar')

    {{-- Inclure la Modale de Connexion/Inscription --}}
    @include('layouts.login_register_modal')

    {{-- Inclure la Modale de recherche pour petit écran --}}
    @include('layouts.search_modal')

    {{-- Overlay partagé pour les modales et sidebars --}}
    @include('layouts.overlay')

    {{-- Inclure le Footer --}}
    @include('layouts.footer')

    @yield('scripts_extra') {{-- Pour des scripts JS supplémentaires spécifiques à une page --}}

</body>

</html>