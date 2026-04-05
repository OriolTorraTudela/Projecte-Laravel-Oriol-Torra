<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory per al model Comment.
 * Genera comentaris de prova associats a imatges i usuaris existents.
 */
class CommentFactory extends Factory
{
    /**
     * Defineix l'estat per defecte d'un comentari generat.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // user_id i image_id s'assignen al seeder per controlar les relacions
            'user_id'  => User::factory(),
            'image_id' => Image::factory(),
            // Comentari curt i realista
            'content'  => fake()->sentence(rand(3, 20)),
        ];
    }
}
