{{-- Modale de Connexion/Inscription --}}
<div class="login-modal-overlay" id="login-modal-overlay">
    <div class="login-modal" id="login-modal">
        <div class="login-modal-header">
            <i class="fas fa-times login-modal-close-btn" id="login-modal-close-btn"></i>
            <div class="login-tabs">
                <button class="tab-button active" id="login-tab-btn">Connexion</button>
                <button class="tab-button" id="register-tab-btn">Inscription</button>
            </div>
        </div>
        <div class="login-modal-content">
            <h2 class="login-modal-welcome">Bienvenue sur Résidences Nehemie</h2>

            {{-- Section de Connexion --}}
            <div id="login-section" class="form-section active">
                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf
                    {{-- Zone pour afficher les messages de succès ou d'erreur --}}
                    <div class="form-message"></div>
                    <div class="form-group">
                        <input type="text" id="login-email" name="email" placeholder="Entrez votre email ou nom d'utilisateur" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" class="button login-continue-btn">Se connecter</button>
                </form>
            </div>

            {{-- Section d'Inscription --}}
            <div id="register-section" class="form-section">
                <form action="{{route('register')}}" method="POST" class="register-form">
                    @csrf
                    {{-- Zone pour afficher les messages de succès ou d'erreur --}}
                    <div class="form-message"></div>
                    <div class="form-group">
                        <input type="text" id="register-name" name="name" placeholder="Entrez votre nom complet" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" id="register-phone" name="phone_number" placeholder="Entrez votre contact" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="register-email" name="email" placeholder="Entrez votre email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="register-password" name="password" placeholder="Créez un mot de passe" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="register-confirm-password" name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
                    </div>
                    <button type="submit" class="button login-continue-btn">S'inscrire</button>
                </form>
            </div>

            <!-- <div class="or-separator"><span>ou</span></div>
            <button class="social-login-btn google-btn">
                <i class="fab fa-google"></i> Continuer avec Google
            </button> -->
        </div>
    </div>
</div>