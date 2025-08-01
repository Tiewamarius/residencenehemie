<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Résidences Nehemie')</title> {{-- Titre dynamique --}}

    {{-- Inclure Vite pour vos assets CSS et JS --}}
    @vite([
        'resources/css/homepage.css',
        'resources/js/homepage.js',

        'resources/css/detailsAppart.css',
        'resources/js/detailsAppart.js',

        'resources/css/homeUser.css',
        'resources/js/homeUser.js'
        ])

    {{-- Font Awesome pour les icônes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Si vous utilisez des icônes Unicons (comme uil-times), assurez-vous d'inclure leur CDN --}}
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    @yield('head_extra') {{-- Pour des styles ou scripts supplémentaires spécifiques à une page --}}

</head>
<body>

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