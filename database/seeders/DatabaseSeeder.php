<?php

namespace Database\Seeders;

use App\Models\User; // Assurez-vous d'importer les modèles si vous les créez directement ici
use App\Models\Residence;
use App\Models\Type;
use App\Models\Equipment;
use App\Models\ResidenceImage;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\StaticPage;
use App\Models\RoomTypeDailyPriceAvailability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Role; Importez le modèle Role de Spatie

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appelez le RolesAndPermissionsSeeder en premier pour vous assurer que les rôles existent
        // $this->call([
        //     RolesAndPermissionsSeeder::class,
        // ]);

        // Créer un utilisateur administrateur et lui assigner le rôle 'admin'
        // $adminUser = User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('password'), // Mot de passe pour l'admin
        //     // Ne définissez plus la colonne 'role' ici si Spatie gère les rôles logiques
        // ]);
        // $adminUser->assignRole('admin'); // Assigner le rôle Spatie 'admin'

        // Créer un utilisateur gestionnaire d'hôtel et lui assigner le rôle 'manager'
        $hotelManagerUser = User::factory()->create([
            'name' => 'manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            // Ne définissez plus la colonne 'role' ici
        ]);
        $hotelManagerUser->assignRole(''); // Assigner le rôle Spatie 'manager'

        // Créer quelques utilisateurs clients et leur assigner le rôle 'client'
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('client'); // Assigner le rôle Spatie 'client'
        });

        // Créer des équipements
        Equipment::factory(9)->create();

        // Créer 10 résidences (hôtels/appartements)
        $residences = Residence::factory(10)->create();

        // Pour chaque résidence, créer des types de chambres et attacher des équipements/images
        $residences->each(function ($residence) {
            // Créer 3 à 5 types de chambres pour chaque résidence
            $roomTypes = Type::factory(fake()->numberBetween(3, 5))->create([
                'residence_id' => $residence->id,
            ]);

            // Attacher 2 à 5 équipements aléatoires à chaque résidence
            $equipments = Equipment::inRandomOrder()->limit(fake()->numberBetween(2, 5))->get();
            $residence->equipment()->attach($equipments->pluck('id')); // Liaison Many-to-Many

            // Ajouter 3 à 7 images pour chaque résidence
            ResidenceImage::factory(fake()->numberBetween(3, 7))->create([
                'residence_id' => $residence->id,
            ]);

            // Pour chaque type de chambre, ajouter des prix/disponibilités quotidiens pour les 3 prochains mois
            $roomTypes->each(function ($roomType) {
                $currentDate = now();
                for ($i = 0; $i < 90; $i++) { // 90 jours
                    RoomTypeDailyPriceAvailability::factory()->create([
                        'type_id' => $roomType->id,
                        'date' => $currentDate->copy()->addDays($i)->format('Y-m-d'),
                        'price' => $roomType->prix_base + fake()->randomFloat(2, -10000, 20000), // Prix autour du prix de base
                        'available_units' => fake()->numberBetween(0, 5), // Simule la disponibilité
                    ]);
                }
            });
        });

        // Créer des réservations
        // Note: La création de bookings via factory() va elle-même créer des User, Residence, Type si non existants.
        // Pour lier à des existants, il faut passer les IDs. On va faire simple ici pour le seeding.
        Booking::factory(50)->create();

        // Créer des paiements (certains peuvent être liés aux bookings existants)
        Payment::factory(40)->create();

        // Créer des avis
        Review::factory(30)->create();

        // Créer des favoris
        Favorite::factory(25)->create();

        // Créer des messages
        Message::factory(20)->create();

        // Créer des pages statiques
        StaticPage::factory(5)->create();
    }
}
