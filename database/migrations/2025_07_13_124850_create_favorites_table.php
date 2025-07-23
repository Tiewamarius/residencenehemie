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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clé étrangère vers la table 'users'
            $table->foreignId('user_id')
                  ->constrained('users') // L'utilisateur qui ajoute en favori
                  ->onDelete('cascade'); // Si l'utilisateur est supprimé, ses favoris le sont aussi

            // Utilisation de morphs pour une relation polymorphique
            // Cela crée AUTOMATIQUEMENT les colonnes 'favoritable_id' (BIGINT UNSIGNED)
            // et 'favoritable_type' (VARCHAR)
            $table->morphs('favoritable');

            $table->timestamps();

            // Contrainte d'unicité pour éviter qu'un utilisateur ne mette le même élément en favori plusieurs fois
            $table->unique(['user_id', 'favoritable_id', 'favoritable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};