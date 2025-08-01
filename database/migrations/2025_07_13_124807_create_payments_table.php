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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clé étrangère vers la table 'bookings'
            $table->foreignId('booking_id')
                  ->constrained('bookings') // Le paiement est lié à une réservation
                  ->onDelete('cascade');    // Si la réservation est supprimée, le paiement l'est aussi

            $table->string('transaction_id')->unique()->nullable(); // ID de transaction de la passerelle de paiement
            $table->decimal('montant', 10, 2); // Montant du paiement
            $table->string('devise', 3)->default('XOF'); // Devise (ex: XOF, EUR, USD)
            $table->string('methode_paiement')->nullable(); // Ex: 'Carte Bancaire', 'Mobile Money', 'Virement'
            $table->string('statut')->default('pending'); // Statut du paiement (ex: 'pending', 'completed', 'failed', 'refunded')
            $table->timestamp('date_paiement')->nullable(); // Date et heure du paiement

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};