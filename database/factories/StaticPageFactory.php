<?php

namespace Database\Factories;

use App\Models\StaticPage;
use App\Models\User; // Si vous utilisez auteur_id
use Illuminate\Database\Eloquent\Factories\Factory;

class StaticPageFactory extends Factory
{
    protected $model = StaticPage::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3), // 'title' pour le titre
            'slug' => fake()->unique()->slug(),
            'content' => fake()->paragraphs(5, true), // 'content' pour le contenu
            'meta_description' => fake()->sentence(10), // Correspond à la nouvelle colonne
            'is_published' => fake()->boolean(90), // Correspond à la nouvelle colonne
            'auteur_id' => User::inRandomOrder()->first()->id, // Si vous l'utilisez, assurez-vous que des users sont seedés
        ];
    }

    // Ajoutez des états si nécessaire, par exemple pour une page non publiée
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}