<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory per al model User.
 * Genera usuaris de prova amb dades falses però coherents.
 */
class UserFactory extends Factory
{
    /**
     * Contrasenya per defecte per a tots els usuaris de prova.
     */
    protected static ?string $password;

    /**
     * Defineix l'estat per defecte d'un usuari generat.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name    = fake()->firstName();
        $surname = fake()->lastName();

        return [
            'role'              => 'user',
            'name'              => $name,
            'surname'           => $surname,
            // Nick únic: nom + part del cognon en minúscules
            'nick'              => strtolower($name . rand(1, 999)),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // Tothom té la mateixa contrasenya 'password' per facilitar les proves
            'password'          => static::$password ??= Hash::make('password'),
            'phone_number'      => fake()->numerify('6########'), // Telèfon espanyol
            'image'             => null, // Sense avatar per defecte
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Estat per a un usuari amb email no verificat.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Estat per a un usuari administrador.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
