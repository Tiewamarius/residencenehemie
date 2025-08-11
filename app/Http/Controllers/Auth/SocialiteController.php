<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirige l'utilisateur vers la page de connexion Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Gère le retour de Google après la connexion.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Crée ou trouve l'utilisateur dans la base de données
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)), // Génère un mot de passe aléatoire
                ]
            );

            // Connecte l'utilisateur
            Auth::login($user, true);

            // Redirige l'utilisateur vers la page d'accueil ou la page précédente
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['message' => 'La connexion via Google a échoué.']);
        }
    }
}
