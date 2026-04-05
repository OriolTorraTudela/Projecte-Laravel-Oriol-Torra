<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory per al model Like.
 * La unicitat (user_id + image_id) es controla des del seeder.
 */
class LikeFactory extends Factory
{
    /**
     * Defineix l'estat per defecte d'un like generat.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // user_id i image_id s'assignen al seeder manualment
            'user_id'  => User::factory(),
            'image_id' => Image::factory(),
        ];
    }
}
