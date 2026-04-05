<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migració per crear la taula likes.
 * Un usuari pot fer like a una imatge (màxim 1 like per usuari per imatge).
 */
return new class extends Migration
{
    /**
     * Crea la taula likes.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();

            // Usuari que ha fet el like
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Imatge que ha rebut el like
            $table->foreignId('image_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();

            // Restricció única: un usuari no pot fer like dues vegades a la mateixa imatge
            $table->unique(['user_id', 'image_id']);
        });
    }

    /**
     * Elimina la taula likes.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
