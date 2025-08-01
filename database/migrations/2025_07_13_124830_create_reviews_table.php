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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clés étrangères
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('residence_id')
                  ->constrained('residences')
                  ->onDelete('cascade');

            // booking_id est optionnel, car un avis peut être laissé sans réservation directe parfois
            $table->foreignId('booking_id')
                  ->nullable() // Peut être nul si l'avis n'est pas lié à une réservation spécifique
                  ->constrained('bookings')
                  ->onDelete('set null'); // Si la réservation est supprimée, la clé booking_id est mise à null

            $table->integer('note'); // Note (ex: 1 à 5)
            $table->text('commentaire'); // Le texte de l'avis
            $table->string('statut')->default('pending'); // Statut de l'avis (pending, approved, declined)

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};