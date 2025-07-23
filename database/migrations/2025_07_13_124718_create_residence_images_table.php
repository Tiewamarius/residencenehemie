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
        Schema::create('residence_images', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clé étrangère vers la table 'residences'
            $table->foreignId('residence_id')
                  ->constrained('residences') // Contraint à la table 'residences'
                  ->onDelete('cascade');     // Si une résidence est supprimée, ses images sont supprimées

            $table->string('chemin_image'); // Chemin ou URL de l'image
            $table->string('description')->nullable(); // Description de l'image
            $table->boolean('est_principale')->default(false); // Indique si c'est l'image principale
            $table->integer('order')->default(0);
            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_images');
    }
};