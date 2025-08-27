<?php

namespace App\Http\Controllers;

use App\Models\Residence;
use App\Models\Favorite;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Affiche la page des favoris de l'utilisateur.
     */
    public function favoris()
    {
        // On récupère les résidences favorites de l'utilisateur authentifié
        $favorites = Auth::user()->favorites()->where('favoritable_type', Residence::class)->get();

        // On charge les résidences associées aux favoris pour avoir leurs détails
        $favoriteResidences = $favorites->map(function ($favorite) {
            return $favorite->favoritable;
        });

        return view('Pages.favoris', compact('favoriteResidences'));
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
     * Ajoute ou retire une résidence des favoris de l'utilisateur.
     */
    public function toggleFavori(Residence $residence)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        // Vérifier si la résidence est déjà dans les favoris de l'utilisateur
        $favorite = $user->favorites()
            ->where('favoritable_id', $residence->id)
            ->where('favoritable_type', Residence::class)
            ->first();

        if ($favorite) {
            // Si oui, la retirer en supprimant l'entrée
            $favorite->delete();
            return response()->json(['status' => 'removed', 'message' => 'Retiré des favoris']);
        } else {
            // Sinon, l'ajouter en créant une nouvelle entrée
            $user->favorites()->create([
                'favoritable_id' => $residence->id,
                'favoritable_type' => Residence::class,
            ]);
            return response()->json(['status' => 'added', 'message' => 'Ajouté aux favoris']);
        }
    }



    /**
     * Gère la recherche d'appartements.
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'destination'   => 'nullable|string',
            'date_arrivee'  => 'required|date',
            'date_depart'   => 'required|date|after:date_arrivee',
            'voyageurs'     => 'nullable|integer|min:1',
        ]);

        $residences = \App\Models\Residence::with(['images', 'types'])
            ->when(!empty($validated['destination']), function ($q) use ($validated) {
                $q->where('ville', 'like', '%' . $validated['destination'] . '%');
            })
            ->whereDoesntHave('bookings', function ($q) use ($validated) {
                $q->where('statut', '!=', 'Cancelled') // on exclut les annulées
                    ->where(function ($query) use ($validated) {
                        $query->whereBetween('date_arrivee', [$validated['date_arrivee'], $validated['date_depart']])
                            ->orWhereBetween('date_depart', [$validated['date_arrivee'], $validated['date_depart']])
                            ->orWhere(function ($sub) use ($validated) {
                                $sub->where('date_arrivee', '<=', $validated['date_arrivee'])
                                    ->where('date_depart', '>=', $validated['date_depart']);
                            });
                    });
            })
            ->get();

        return view('searchAppart', compact('residences'));
    }
    public function searchAppart()
    {
        // On charge les résidences avec les images et types pour éviter les requêtes N+1
        $residences = Residence::with(['images', 'types'])->get();

        return  view('searchAppart', compact('residences'));
    }
}
