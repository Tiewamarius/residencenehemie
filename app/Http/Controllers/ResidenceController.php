<?php

namespace App\Http\Controllers;

use App\Models\Residence; // N'oubliez pas d'importer votre modèle Residence
use Illuminate\Http\Request;

class ResidenceController extends Controller
{
    /**
     * Affiche la liste de toutes les résidences.
     */
    public function index()
    {
        $residences = Residence::with('images', 'types')->get(); // Ou Residence::paginate(10);

        // Passe les résidences à la vue
        return view('Pages.index', compact('residences'));
    }


    public function HomePage()
    {
        $residences = Residence::with(['images', 'types'])->get();

        return view('Pages/HomePage', compact('residences'));
    }

    /**
     * Affiche les détails d'une résidence spécifique.
     */
    public function detailsAppart(Residence $residence)
    {
        $residences = Residence::with(['images', 'types'])->get();
        // 1. Charge toutes les relations nécessaires pour la vue
        $residence->load([
            'images',        // Pour la galerie d'images
            // 'nombre_chambres',
            'types',         // Pour les infos sur les chambres, lits, salles de bain, prix
            'Equipment',     // Pour la liste des équipements
            'reviews.user',  // Pour les avis et l'utilisateur qui les a postés
            'user'           // Pour les informations sur l'hôte de la résidence
        ]);

        // 2. Passe le modèle $residence (avec ses relations chargées) à la vue
        return view('Pages.detailsAppart', compact('residence', 'residences'));
    }

    public function favoris(Residence $residence)
    {
        // 2. Passe le modèle $residence (avec ses relations chargées) à la vue
        return view('Pages.favoris');
    }
}
