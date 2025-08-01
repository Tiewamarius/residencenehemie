<?php

namespace Database\Factories;

use App\Models\Residence;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidenceFactory extends Factory
{
    protected $model = Residence::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->company() . ' Hotel & Suites',
            'description' => fake()->paragraphs(3, true),
            'adresse' => fake()->streetAddress(),
            'ville' => fake()->city(),
            'pays' => fake()->country(),
            'latitude' => fake()->latitude(5.0, 6.0), // Exemple pour la région d'Abidjan (Bingerville est autour de 5.37 N, 4.02 W)
            'longitude' => fake()->longitude(-4.5, -3.5),
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'note_moyenne' => fake()->randomFloat(1, 3.0, 5.0), // Note de 3.0 à 5.0
        ];
    }
}