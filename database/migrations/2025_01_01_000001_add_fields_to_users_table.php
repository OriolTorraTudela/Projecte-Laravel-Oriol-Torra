<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migració per afegir camps addicionals a la taula users.
 * Camps: role, surname, nick, image (avatar), phone_number
 */
return new class extends Migration
{
    /**
     * Executa la migració: afegeix columnes noves a la taula users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rol de l'usuari: per defecte 'user', pot ser 'admin'
            $table->string('role')->default('user')->after('id');

            // Cognom de l'usuari (opcional)
            $table->string('surname')->nullable()->after('name');

            // Nom d'usuari únic (nick / username)
            $table->string('nick')->unique()->nullable()->after('surname');

            // Ruta de l'avatar de l'usuari (opcional)
            $table->string('image')->nullable()->after('nick');

            // Número de telèfon (opcional)
            $table->string('phone_number')->nullable()->after('email');
        });
    }

    /**
     * Reverteix la migració: elimina les columnes afegides.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'surname', 'nick', 'image', 'phone_number']);
        });
    }
};
