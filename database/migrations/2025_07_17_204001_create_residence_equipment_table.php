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
        Schema::create('residence_equipment', function (Blueprint $table) {
            // Clé étrangère pour l'ID de la résidence
            $table->foreignId('residence_id')
                  ->constrained('residences') // Contraint à la table 'residences'
                  ->onDelete('cascade');     // Si une résidence est supprimée, les liens sont supprimés

            // Clé étrangère pour l'ID de l'équipement
            $table->foreignId('equipment_id')
                  ->constrained('equipment')  // Contraint à la table 'equipment'
                  ->onDelete('cascade');     // Si un équipement est supprimé, les liens sont supprimés

            // Définition d'une clé primaire composite pour éviter les doublons
            $table->primary(['residence_id', 'equipment_id']);

            $table->timestamps(); // Ajoute created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_equipment');
    }
};