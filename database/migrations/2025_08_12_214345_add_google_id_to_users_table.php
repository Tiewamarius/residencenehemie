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
        Schema::table('users', function (Blueprint $table) {
            // Ajoute une nouvelle colonne pour l'identifiant Google.
            // Elle est nullable car les utilisateurs existants n'auront pas cette valeur.
            // Elle est unique pour s'assurer qu'un identifiant Google n'est pas utilisé deux fois.
            $table->string('google_id')->nullable()->after('email')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprime la colonne en cas de rollback de la migration.
            $table->dropColumn('google_id');
        });
    }
};
