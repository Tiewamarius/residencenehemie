<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // Mot de passe par défaut : 'password'
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(['client', 'admin', 'hotel_manager']), // Garde le rôle
            'profile_picture' => fake()->imageUrl(640, 480, 'people', true), // URL d'image factice
            'phone_number' => fake()->phoneNumber(),       // Nouveau nom
            'address' => fake()->address(),                 // Nouveau nom
            'description' => fake()->optional()->paragraph(2), // Description optionnelle
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State pour un administrateur.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State pour un gestionnaire d'hôtel.
     */
    public function hotelManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'hotel_manager',
        ]);
    }
}