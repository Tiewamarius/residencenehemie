<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Residence;
use App\Models\Type; // Votre modèle RoomType
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        // Choisir aléatoirement entre Residence et Type (RoomType)
        $favoritableType = fake()->randomElement([
            Residence::class,
            Type::class,
        ]);

        $favoritableId = null;
        if ($favoritableType === Residence::class) {
            $favoritableId = Residence::factory()->create()->id;
        } elseif ($favoritableType === Type::class) {
            $favoritableId = Type::factory()->create()->id;
        }

        return [
            'user_id' => User::factory(),
            'favoritable_id' => $favoritableId,
            'favoritable_type' => $favoritableType,
        ];
    }
}