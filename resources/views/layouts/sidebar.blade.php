{{-- Sidebar (Menu Mobile) --}}
<div class="sidebar" id="sidebar">
    <div class="sidebar_header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Résidences Nehemie" class="sidebar_logo" onerror="this.onerror=null;this.src='https://placehold.co/150x50/FFFFFF/000000?text=LOGO';">
        <i class="fas fa-times sidebar_close_btn" id="sidebar-close-btn"></i>
    </div>
    <ul class="sidebar_nav_items">
        <li><a href="{{ url('/') }}" class="sidebar_nav_link">Accueil</a></li>
        <li><a href="#" class="sidebar_nav_link">Appartements</a></li>
        <li><a href="#" class="sidebar_nav_link" id="contact-open-sidebar-btn">Contact</a></li>
        @guest
            {{-- <li><a href="{{ route('register') }}" class="sidebar_nav_link"><i class="fas fa-user-plus"></i> S'inscrire</a></li> --}}
            {{-- <li><a href="{{ route('login') }}" class="sidebar_nav_link"><i class="fas fa-sign-in-alt"></i> Se connecter</a></li> --}}
        @else
            <li><a href="{{ route('favorites.index') }}" class="sidebar_nav_link"><i class="fas fa-heart"></i> Favoris</a></li>
            <li><a href="{{ route('dashboards') }}" class="sidebar_nav_link"><i class="fas fa-user-circle"></i> Mon Compte</a></li>
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