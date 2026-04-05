<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory per al model Image.
 * Genera imatges de prova associades a usuaris existents.
 * Les imatges es descarreguen de picsum.photos (Lorem Picsum) i es desen a storage.
 */
class ImageFactory extends Factory
{
    /**
     * Defineix l'estat per defecte d'una imatge generada.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Genera un ID aleatori per a Lorem Picsum (imatges públiques de prova)
        $imageId   = rand(1, 1000);
        $imageName = 'images/' . fake()->uuid() . '.jpg';

        // Descarrega la imatge i la desa a storage/app/public/images
        // Si falla la descàrrega, utilitzem un placeholder local
        try {
            $imageContent = file_get_contents("https://picsum.photos/seed/{$imageId}/800/600");
            if ($imageContent !== false) {
                \Storage::disk('public')->put($imageName, $imageContent);
            } else {
                $imageName = 'images/placeholder.jpg';
            }
        } catch (\Exception $e) {
            // Si no hi ha connexió, usem una imatge de placeholder
            $imageName = 'images/placeholder.jpg';
        }

        return [
            // user_id s'assigna al seeder per controlar les relacions
            'user_id'     => User::factory(),
            'image_path'  => $imageName,
            'description' => fake()->sentence(rand(5, 15)),
        ];
    }
}
