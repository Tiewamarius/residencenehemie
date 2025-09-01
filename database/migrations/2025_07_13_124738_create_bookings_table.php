<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clés étrangères vers les tables 'users', 'residences' et 'types'
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users') // L'utilisateur qui fait la réservation
                ->onDelete('cascade'); // Si l'utilisateur est supprimé, ses réservations le sont aussi

            $table->foreignId('residence_id')
                ->constrained('residences') // La résidence réservée
                ->onDelete('cascade');

            $table->foreignId('type_id')
                ->constrained('types') // Le type de chambre/appartement réservé
                ->onDelete('cascade');

            // Informations sur la période de réservation
            $table->date('date_arrivee');
            $table->date('date_depart');

            // Nombre de personnes
            $table->integer('nombre_adultes');
            $table->integer('nombre_enfants')->default(0);

            // Statut de la réservation (ex: 'pending', 'confirmed', 'cancelled', 'completed')
            $table->string('statut')->default('pending');

            // Prix total de la réservation
            $table->decimal('total_price', 10, 2);
            $table->string('numero_reservation')->unique(); // Le numéro de réservation doit être unique
            $table->json('details_client'); // Pour stocker les détails du client comme JSON
            $table->text('note_client')->nullable(); // Notes ou commentaires du client


            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
