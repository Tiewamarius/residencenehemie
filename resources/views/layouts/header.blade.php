{{-- Header principal --}}
<header class="header">
    <nav class="nav">
        <a href="{{ url('/') }}" class="nav_logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Résidences Nehemie" onerror="this.onerror=null;this.src='https://placehold.co/150x50/FFFFFF/000000?text=Votre+Logo';" style="height: 70px; width: auto;">
        </a>
        <ul class="nav_item">
            <li><a href="{{ url('/') }}" class="nav_link">Accueil</a></li>
            <li><a href="/#appartements" class="nav_link">Appartements</a></li>
            <li><a href="#" class="nav_link" id="contact_open_btn">Contact</a></li>
            <!-- <li><a href="#" class="nav_link" id="search-toggle-btn-desktop"><i class="fas fa-search"></i></a></li> -->

            @guest
            <li><a href="{{ route('login') }}" class="sidebar_nav_link"><i class="fas fa-user-circle"></i></a></li>
            <!-- <li><a href="#" class="nav_link" id="open-login-modal"><i class="fas fa-heart"></i></a></li> -->
            @else
            <!-- <li><a href="{{ route('favorites.index') }}" class="nav_link"><i class="fas fa-heart"></i></a></li> -->
            <li><a href="{{ route('profile.homeUser') }}" class="nav_link"><i class="fas fa-user-circle"></i></a></li>
            <li><a href="#" class="nav_link" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a></li>
            <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            @endguest
        </ul>
        <button class="button" id="form-open">Assistant</button>
        <button class="menu_toggle_btn" id="menu-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>
</header>