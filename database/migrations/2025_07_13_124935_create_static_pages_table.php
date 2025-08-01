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
        Schema::create('static_pages', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // Renommé de 'titre' à 'title'
            $table->string('slug')->unique();
            $table->longText('content'); // Renommé de 'contenu' à 'content'
            $table->text('meta_description')->nullable(); // NOUVELLE COLONNE
            $table->boolean('is_published')->default(false); // NOUVELLE COLONNE

            $table->foreignId('auteur_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('static_pages');
    }
};