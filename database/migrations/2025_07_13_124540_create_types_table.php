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
        Schema::create('types', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clé étrangère vers la table 'residences'
            // C'est cette colonne 'residence_id' qui manquait et causait l'erreur
            $table->foreignId('residence_id')
                  ->constrained('residences') // Contraint à la table 'residences'
                  ->onDelete('cascade');     // Si une résidence est supprimée, ses types de chambres sont supprimés

            // Colonnes pour les informations du type de chambre/appartement
            $table->string('nom'); // Ex: "Chambre Standard", "Suite Familiale", "Appartement F2"
            $table->text('description')->nullable();
            $table->integer('capacite_adultes'); // Nombre max d'adultes
            $table->integer('capacite_enfants')->default(0); // Nombre max d'enfants, par défaut 0
            $table->integer('superficie')->nullable(); // Superficie en m²
            $table->decimal('prix_base', 10, 2); // Prix de base pour ce type de chambre
            $table->integer('nombre_lits')->nullable();
            $table->string('type_lit')->nullable(); // Ex: "King", "Queen", "Double", "Simple"

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};