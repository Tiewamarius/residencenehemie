<?php

namespace App\Http\Controllers;

use App\Models\Residence; // N'oubliez pas d'importer votre modèle Residence
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    // methode search Appart
    public function search(Request $request)
    {
        try {
            // 1. Validation des données du formulaire
            $validated = $request->validate([
                'address' => 'nullable|string|max:255',
                'arrivee' => 'nullable|date',
                'depart' => 'nullable|date|after_or_equal:arrivee',
                'adultes' => 'required|integer|min:1',
                'enfants' => 'required|integer|min:0',
            ]);

            // 2. Début de la requête pour trouver les appartements
            $query = Residence::query();

            // 3. Appliquer le filtre par adresse si elle est fournie
            if (!empty($validated['address'])) {
                $query->where('adresse', 'like', '%' . $validated['address'] . '%');
            }

            // 4. Appliquer les filtres par capacité
            $query->where('nombre_adultes', '>=', $validated['adultes'])
                ->where('nombre_enfants', '>=', $validated['enfants']);

            // 5. Filtrer les appartements non disponibles si les dates sont fournies
            if (!empty($validated['arrivee']) && !empty($validated['depart'])) {
                $query->whereDoesntHave('reservations', function ($q) use ($validated) {
                    $q->where(function ($q2) use ($validated) {
                        $q2->where('date_arrivee', '<=', $validated['depart'])
                            ->where('date_depart', '>=', $validated['arrivee']);
                    });
                });
            }

            // 6. Exécuter la requête et récupérer les résultats
            $apartments = $query->with('images')->get();

            // Journaliser les résultats pour vérifier ce qui est renvoyé
            Log::info('Recherche d\'appartements réussie.', ['count' => $apartments->count()]);

            // 7. Transformer les résultats pour le format JSON attendu par le frontend
            $results = $apartments->map(function ($apartment) {
                $featuredImage = $apartment->images->where('est_principale', true)->first() ?? $apartment->images->first();

                // Journaliser un appartement transformé pour vérifier la structure
                Log::info('Appartement transformé.', ['apartment_id' => $apartment->id, 'featured_image' => $featuredImage ? $featuredImage->chemin_image : 'none']);

                return [
                    'id' => $apartment->id,
                    'nom' => $apartment->nom,
                    'adresse' => $apartment->adresse,
                    'ville' => $apartment->ville,
                    'is_superhost' => $apartment->is_superhost,
                    'rating' => $apartment->rating,
                    'prix_min' => $apartment->prix_min,
                    'featuredImage' => $featuredImage ? asset($featuredImage->chemin_image) : null,
                ];
            });

            // 8. Retourner les résultats en format JSON
            return response()->json($results);
        } catch (\Exception $e) {
            // Journaliser l'erreur pour la trouver dans les logs de Laravel
            Log::error('Erreur lors de la recherche d\'appartements.', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            // Renvoyer une réponse d'erreur générique
            return response()->json(['error' => 'Une erreur est survenue lors de la recherche. Veuillez réessayer.'], 500);
        }
    }
}
