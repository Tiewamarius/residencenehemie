<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResidenceController extends Controller
{
    /**
     * Affiche la page d'accueil avec une liste de résidences.
     */
    public function homePage()
    {
        // On charge les résidences avec les images et types pour éviter les requêtes N+1
        $residences = Residence::with(['images', 'types'])->get();

        return view('Pages.HomePage', compact('residences'));
    }

    /**
     * Affiche la liste de toutes les résidences (index).
     */
    public function index()
    {
        // On utilise la pagination pour ne pas surcharger la page avec trop de données
        $residences = Residence::with('images', 'types')->paginate(10);

        return view('Pgages.index', compact('residences'));
    }



    /**
     * Affiche les détails d'une résidence spécifique.
     * On utilise le "Route Model Binding" pour injecter directement le modèle Residence.
     */
    public function detailsAppart(Residence $residence)
    {
        // Charge toutes les relations nécessaires pour cette résidence unique.
        // C'est plus efficace que de charger TOUTES les résidences puis filtrer.
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

    /**
     * Affiche la page des favoris.
     * Cette méthode ne reçoit pas de paramètre de résidence.
     */
    public function favoris()
    {
        return view('Pages.favoris');
    }

    /**
     * Gère la recherche d'appartements.
     */
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

            // 2. Début de la requête pour trouver les résidences
            $query = Residence::query();

            // 3. Appliquer le filtre par adresse si elle est fournie
            if (!empty($validated['address'])) {
                $query->where('adresse', 'like', '%' . $validated['address'] . '%');
            }

            // 4. Appliquer les filtres par capacité des types de chambres
            // On filtre les résidences qui ont au moins un type de chambre
            // pouvant accueillir le nombre d'adultes et d'enfants spécifié.
            $query->whereHas('types', function ($typeQuery) use ($validated) {
                $typeQuery->where('capacite_adultes', '>=', $validated['adultes'])
                    ->where('capacite_enfants', '>=', $validated['enfants']);
            });

            // 5. Filtrer les résidences non disponibles si les dates sont fournies
            if (!empty($validated['arrivee']) && !empty($validated['depart'])) {
                $query->whereDoesntHave('reservations', function ($q) use ($validated) {
                    $q->where('date_arrivee', '<=', $validated['depart'])
                        ->where('date_depart', '>=', $validated['arrivee']);
                });
            }

            // 6. Exécuter la requête et récupérer les résultats avec les relations nécessaires
            $apartments = $query->with(['images', 'types'])->get();

            // 7. Transformer les résultats pour le format JSON attendu
            $results = $apartments->map(function ($apartment) {
                $featuredImage = $apartment->images->where('est_principale', true)->first() ?? $apartment->images->first();

                // On calcule le prix minimum de la résidence en fonction des types
                $minPrice = $apartment->types->min('prix_base') ?? 0;

                // On renvoie un objet propre pour le frontend
                return [
                    'id' => $apartment->id,
                    'nom' => $apartment->nom,
                    'adresse' => $apartment->adresse,
                    'is_superhost' => $apartment->is_superhost,
                    'rating' => $apartment->avgRating(), // Utilisation d'une méthode potentielle du modèle
                    'prix_min' => $minPrice,
                    'featuredImage' => $featuredImage ? asset($featuredImage->chemin_image) : null,
                ];
            });

            // 8. Retourner les résultats en format JSON
            return response()->json($results);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche d\'appartements.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la recherche. Veuillez réessayer.'
            ], 500);
        }
    }
}
