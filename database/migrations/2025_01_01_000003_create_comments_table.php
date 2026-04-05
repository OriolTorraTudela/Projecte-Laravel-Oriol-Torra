<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migració per crear la taula comments.
 * Els comentaris es fan sobre imatges i pertanyen a un usuari.
 */
return new class extends Migration
{
    /**
     * Crea la taula comments.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Usuari que ha fet el comentari
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Imatge sobre la qual es fa el comentari
            $table->foreignId('image_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Contingut textual del comentari
            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Elimina la taula comments.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
