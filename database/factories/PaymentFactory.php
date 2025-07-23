<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
            'montant' => fake()->randomFloat(2, 50000, 2000000), // Montant du paiement
            'devise' => 'XOF', // Par défaut pour le contexte d'Abidjan
            'methode_paiement' => fake()->randomElement(['Carte Bancaire', 'Mobile Money', 'Virement Bancaire']),
            'statut' => fake()->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'date_paiement' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}