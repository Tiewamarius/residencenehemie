<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    // Dans database/factories/EquipmentFactory.php
public function definition(): array
{
    return [
        'nom' => fake()->unique()->randomElement([
            'Wi-Fi Gratuit',
            'Piscine',
            'Parking',
            'Climatisation',
            'Salle de Sport',
            'Restaurant',
            'Service en Chambre',
            'Navette Aéroport',
            'Petit-déjeuner Inclus',
            'Spa & Bien-être',         // Ajout
            'Blanchisserie',           // Ajout
            'Centre d\'affaires',      // Ajout
            'Bar/Lounge',              // Ajout
            'Réception 24h/24',         // Ajout
            'Ascenseur',               // Ajout
            'Chambres familiales',     // Ajout
            'Accès pour personnes à mobilité réduite', // Ajout
            'Mini-bar en chambre',     // Ajout
            'Cuisine équipée',         // Ajout (si pour appartements)
            'Terrasse',                // Ajout
        ]),
        'icone' => fake()->imageUrl(30, 30),
    ];
}
}