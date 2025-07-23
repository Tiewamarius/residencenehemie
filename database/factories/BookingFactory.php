<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Residence;
use App\Models\Type; // Votre modèle RoomType (pour les types de chambres)
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        // Récupérer un utilisateur existant
        $user = User::inRandomOrder()->first();
        // Si aucun utilisateur n'existe, en créer un (utile pour les tests unitaires isolés)
        if (!$user) {
            $user = User::factory()->create();
        }

        // Récupérer une résidence existante
        $residence = Residence::inRandomOrder()->first();
        // Si aucune résidence n'existe, en créer une
        if (!$residence) {
            $residence = Residence::factory()->create();
        }

        // Récupérer un type de chambre existant lié à la résidence choisie
        $type = Type::where('residence_id', $residence->id)->inRandomOrder()->first();
        // Si aucun type de chambre n'existe pour cette résidence, en créer un
        if (!$type) {
            $type = Type::factory()->for($residence)->create();
        }

        $dateArrivee = fake()->dateTimeBetween('now', '+3 months');
        // S'assurer que la date de départ est après la date d'arrivée
        $dateDepart = fake()->dateTimeBetween($dateArrivee, $dateArrivee->format('Y-m-d') . ' +7 days');

        // Calcul du prix total basé sur le prix de base du type et la durée
        $nbNuits = $dateArrivee->diff($dateDepart)->days;
        // Assurez-vous que $type->prix_base est un nombre valide
        $prixTotalCalcule = $type->prix_base * $nbNuits;
        // Utilisation de fake()->randomFloat pour simuler une variation ou si prix_base est nul
        $prixTotal = fake()->randomFloat(2, max(50000, $prixTotalCalcule * 0.8), min(2000000, $prixTotalCalcule * 1.2));


        return [
            'user_id' => $user->id, // Utilise l'ID de l'utilisateur existant/créé
            'residence_id' => $residence->id, // Utilise l'ID de la résidence existante/créée
            'type_id' => $type->id, // Utilise l'ID du type de chambre existant/créé
            'date_arrivee' => $dateArrivee,
            'date_depart' => $dateDepart,
            'nombre_adultes' => fake()->numberBetween(1, $type->capacite_adultes), // Respecte la capacité
            'nombre_enfants' => fake()->numberBetween(0, $type->capacite_enfants), // Respecte la capacité
            'total_price' => $prixTotal, // <-- CORRECTION ICI : 'total_price' au lieu de 'prix_total'
            'statut' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'checked_in', 'checked_out', 'completed']),
            'numero_reservation' => 'RES-' . strtoupper(Str::random(8)),
            'details_client' => json_encode([
                'nom_complet' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
            ]),
            'note_client' => fake()->optional()->paragraph(1),
        ];
    }
}