<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Favorite;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        // Charger les données utiles pour la page
        $residences = Residence::with(['images', 'types'])->get();

        $residence->load([
            'images',
            'types',
            'Equipment',        // conserve la casse telle qu'utilisée dans ta vue
            'reviews.user',
            'user'
        ]);

        // Récupération des réservations confirmées ou payées pour cette résidence
        // On prépare des ranges {from, to} au format Y-m-d
        // Note: côté JS, on désactive [from, to-1] pour permettre une arrivée le jour du départ précédent
        $bookedDateRanges = $residence->bookings()
            ->whereIn('statut', ['confirmed', 'paid'])
            ->get(['date_arrivee', 'date_depart'])
            ->map(function ($b) {
                $from = \Carbon\Carbon::parse($b->date_arrivee)->format('Y-m-d');
                $to   = \Carbon\Carbon::parse($b->date_depart)->format('Y-m-d');
                return [
                    'from' => $from,
                    'to'   => $to,
                ];
            })
            ->values();

        return view('Pages.detailsAppart', compact('residence', 'residences', 'bookedDateRanges'));
    }


    /**
     * Affiche la page des favoris.
     * Cette méthode ne reçoit pas de paramètre de résidence.
     */
    public function favoris()
    {
        return view('Pages.favoris');
    }


    // Fonction d'ajout de résidence en favoris avec la relation polymorphe
    public function storefavoris(Residence $residence)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Veuillez vous connecter pour ajouter des favoris.'], 401);
        }

        try {
            $user = Auth::user();
            // Vérifier si la résidence est déjà un favori de cet utilisateur en utilisant la relation polymorphe
            if ($user->favorites()->where('favoritable_id', $residence->id)->where('favoritable_type', Residence::class)->exists()) {
                return response()->json(['message' => 'La résidence est déjà en favoris.'], 409);
            }

            // Créer une nouvelle instance de favori en utilisant la relation de l'utilisateur
            $favorite = new Favorite();
            $favorite->favoritable_id = $residence->id;
            $favorite->favoritable_type = Residence::class;
            $user->favorites()->save($favorite);

            return response()->json(['message' => 'Résidence ajoutée aux favoris.'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout aux favoris.', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            return response()->json(['message' => 'Erreur lors de l\'ajout aux favoris.'], 500);
        }
    }

    // Fonction de suppression de résidence des favoris avec la relation polymorphe
    public function destroyfavoris(Residence $residence)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Veuillez vous connecter pour gérer vos favoris.'], 401);
        }

        try {
            $user = Auth::user();
            // Trouver et supprimer l'entrée dans la table des favoris
            $favorite = $user->favorites()->where('favoritable_id', $residence->id)->where('favoritable_type', Residence::class)->first();

            if (!$favorite) {
                return response()->json(['message' => 'Cette résidence n\'est pas dans vos favoris.'], 404);
            }

            $favorite->delete();

            return response()->json(['message' => 'Résidence supprimée des favoris.'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression des favoris.', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            return response()->json(['message' => 'Erreur lors de la suppression des favoris.'], 500);
        }
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
                'arrivee' => 'required|date',
                'depart' => 'required|date|after_or_equal:arrivee',
                'adultes' => 'nullable|integer|min:1',
                'enfants' => 'nullable|integer|min:0',
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
    public function searchAppart()
    {
        // On charge les résidences avec les images et types pour éviter les requêtes N+1
        $residences = Residence::with(['images', 'types'])->get();

        return  view('searchAppart', compact('residences'));
    }
}
