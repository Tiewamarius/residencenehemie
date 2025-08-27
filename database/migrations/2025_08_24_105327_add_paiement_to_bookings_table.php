<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        // On modifie la table 'bookings'
        Schema::table('bookings', function (Blueprint $table) {
            // Ajoute une nouvelle colonne 'paiement'
            // de type chaîne de caractères (string).
            // La valeur par défaut est 'unPaid' si aucune valeur n'est spécifiée lors de la création d'une nouvelle réservation.
            // On la place après la colonne 'status' (vous pouvez la placer où vous voulez en changeant le nom de la colonne).
            $table->string('paiement')->default('unPaid')->after('status');
        });
    }

    /**
     * Annule les migrations.
     *
     * @return void
     */
    public function down()
    {
        // On modifie la table 'bookings'
        Schema::table('bookings', function (Blueprint $table) {
            // Supprime la colonne 'paiement' en cas de rollback de la migration.
            $table->dropColumn('paiement');
        });
    }
};
