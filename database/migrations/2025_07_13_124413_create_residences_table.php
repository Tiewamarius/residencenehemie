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
        Schema::create('residences', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Colonnes pour les informations de base de la résidence
            $table->string('nom'); // Nom de la résidence/hôtel (obligatoire)
            $table->text('description')->nullable(); // Description détaillée
            $table->string('adresse')->nullable(); // Adresse physique
            $table->string('ville')->nullable(); // Ville (pour la recherche)
            $table->string('pays')->nullable(); // Pays (pour la recherche)

            // Coordonnées géographiques (utiles pour les cartes)
            $table->decimal('latitude', 10, 7)->nullable(); // Latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Longitude

            // Informations de contact
            $table->string('telephone')->nullable();
            $table->string('email')->unique()->nullable(); // Email unique pour la résidence

            $table->integer('nombre_chambres')->default(0);
            // Note moyenne des avis
            $table->decimal('note_moyenne', 2, 1)->default(0.0); // Ex: 4.5, par défaut 0.0

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residences');
    }
};