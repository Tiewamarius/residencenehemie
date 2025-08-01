<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Residence;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'residence_id' => Residence::factory(),
            'booking_id' => Booking::factory(), // Optionnel, si l'avis est lié à une réservation
            'note' => fake()->numberBetween(1, 5),
            'commentaire' => fake()->paragraph(3),
            'statut' => fake()->randomElement(['pending', 'approved', 'declined']),
        ];
    }
}