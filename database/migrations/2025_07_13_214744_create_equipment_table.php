<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id(); // La clé primaire 'id' que residence_equipment va référencer
            $table->string('nom')->unique(); // Nom de l'équipement (ex: Wi-Fi, Piscine)
            $table->string('icone')->nullable(); // Optionnel: pour une icône
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};