<?php

namespace Database\Factories;

use App\Models\Type; // Assurez-vous d'importer votre modèle 'Type'
use App\Models\Residence; // Pour lier à une résidence existante
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeFactory extends Factory
{
    protected $model = Type::class;

    public function definition(): array
    {
        return [
            'residence_id' => Residence::factory(), // Va créer une nouvelle résidence si aucune n'est passée
            'nom' => fake()->randomElement(['Chambre Standard', 'Suite Junior', 'Appartement F2', 'Penthouse', 'Villa']),
            'description' => fake()->paragraph(),
            'capacite_adultes' => fake()->numberBetween(1, 4),
            'capacite_enfants' => fake()->numberBetween(0, 3),
            'superficie' => fake()->numberBetween(20, 150), // en m²
            'prix_base' => fake()->randomFloat(2, 50000, 60000), // Exemple en XOF
            'nombre_lits' => fake()->numberBetween(1, 3),
            'type_lit' => fake()->randomElement(['King', 'Queen', 'Double', 'Simple']),
        ];
    }
}