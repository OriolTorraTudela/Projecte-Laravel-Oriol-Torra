<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migració per crear la taula images.
 * Emmagatzema les imatges pujades pels usuaris (equivalent als posts d'Instagram).
 */
return new class extends Migration
{
    /**
     * Crea la taula images amb les seves columnes i relacions.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            // Clau forana a la taula users (qui ha pujat la imatge)
            // onDelete('cascade'): si s'elimina l'usuari, s'eliminen les seves imatges
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Ruta relativa dins de storage/app/public on es guarda la imatge
            $table->string('image_path');

            // Descripció opcional de la imatge
            $table->text('description')->nullable();

            // Camps de control de temps (created_at i updated_at)
            $table->timestamps();
        });
    }

    /**
     * Elimina la taula images (reverteix la migració).
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
