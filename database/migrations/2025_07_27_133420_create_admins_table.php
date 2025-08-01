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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // Ajout des colonnes spécifiques au projet, basées sur le schéma de base de données
            $table->string('role')->default('Manager'); // Rôle par défaut 'client'
            $table->string('profile_picture')->nullable(); // Chemin vers la photo de profil
            $table->string('phone_number')->nullable();   // Numéro de téléphone
            $table->string('address')->nullable();        // Adresse physique
            $table->text('description')->nullable();      // Courte description (pour les propriétaires)

            $table->rememberToken();
            $table->timestamps(); // Ajoute created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
