<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_type_daily_prices_availability', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clé étrangère vers la table 'types' (vos room_types)
            $table->foreignId('type_id') // Utilisez 'type_id' pour faire référence à 'types'
                  ->constrained('types')   // Contraint à la table 'types'
                  ->onDelete('cascade');   // Si un type de chambre est supprimé, les prix associés sont supprimés

            $table->date('date');           // La date spécifique pour laquelle le prix et la dispo s'appliquent
            $table->decimal('price', 10, 2); // Le prix pour ce type de chambre/appartement ce jour-là
            $table->integer('available_units'); // Le nombre d'unités de ce type disponibles pour cette date

            // Ajoute une contrainte unique pour éviter les doublons (un seul prix/dispo par type et par date)
            $table->unique(['type_id', 'date']);

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_daily_prices_availability');
    }
};