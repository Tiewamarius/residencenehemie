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
        // Récupère toutes les résidences de la base de données
        // Vous pouvez ajouter de la pagination si vous avez beaucoup de résidences
        $residences = Residence::with('images')->get(); // Ou Residence::paginate(10);

        // Passe les résidences à la vue
        return view('Pages.index', compact('residences'));
    }

    
}