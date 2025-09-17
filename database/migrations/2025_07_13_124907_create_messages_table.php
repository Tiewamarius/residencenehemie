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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Clés étrangères vers la table 'users' pour l'expéditeur et le destinataire
            $table->foreignId('sender_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si l'expéditeur est supprimé, ses messages envoyés le sont aussi

            $table->foreignId('receiver_id')
                ->constrained('users')
                ->onDelete('cascade'); // Si le destinataire est supprimé, ses messages reçus le sont aussi

            $table->string('subject')->nullable(); // Sujet du message
            $table->text('content'); // Contenu du message
            $table->timestamp('read_at')->nullable(); // Date et heure de lecture du message

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
