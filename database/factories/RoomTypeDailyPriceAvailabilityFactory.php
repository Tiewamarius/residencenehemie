<?php

namespace Database\Factories;

use App\Models\RoomTypeDailyPriceAvailability;
use App\Models\Type; // Votre modèle RoomType
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeDailyPriceAvailabilityFactory extends Factory
{
    protected $model = RoomTypeDailyPriceAvailability::class;

    public function definition(): array
    {
        $startDate = now()->addDays(fake()->numberBetween(0, 30));
        $endDate = $startDate->copy()->addDays(fake()->numberBetween(1, 10));

        return [
            'type_id' => Type::factory(), // Va créer un type si besoin
            'date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'price' => fake()->randomFloat(2, 40000, 600000), // Prix quotidien en XOF
            'available_units' => fake()->numberBetween(1, 10),
        ];
    }
}