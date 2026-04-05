<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * DatabaseSeeder — pobla la base de dades amb dades de prova.
 *
 * Ordre d'execució:
 *  1. Crea un usuari de prova fix (per facilitar el login durant el vídeo)
 *  2. Crea 9 usuaris aleatoris (total 10 usuaris)
 *  3. Per cada usuari, crea entre 2 i 5 imatges
 *  4. Per cada imatge, crea entre 1 i 4 comentaris d'usuaris aleatoris
 *  5. Per cada imatge, assigna likes aleatoris (sense duplicats)
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executa els seeders de la base de dades.
     */
    public function run(): void
    {
        // Assegurem que existeix la carpeta d'imatges a storage
        Storage::disk('public')->makeDirectory('images');
        Storage::disk('public')->makeDirectory('avatars');

        // ── 1. Usuari de prova fix ────────────────────────────────────
        // Facilita el login durant el vídeo de presentació
        $testUser = User::factory()->create([
            'name'     => 'Test',
            'surname'  => 'User',
            'nick'     => 'testuser',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
            'role'     => 'user',
        ]);

        // ── 2. Usuaris aleatoris ──────────────────────────────────────
        $users = User::factory(9)->create();

        // Afegim l'usuari fix a la col·lecció per tenir tots els usuaris
        $allUsers = $users->push($testUser);

        // ── 3. Imatges per cada usuari ────────────────────────────────
        foreach ($allUsers as $user) {
            // Cada usuari té entre 2 i 5 imatges
            $imageCount = rand(2, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                // Genera un nom de fitxer únic
                $imageId   = rand(1, 1000);
                $imageName = 'images/' . \Illuminate\Support\Str::uuid() . '.jpg';

                // Intenta descarregar una imatge real de Lorem Picsum
                try {
                    $content = @file_get_contents("https://picsum.photos/seed/{$imageId}/800/600");
                    if ($content !== false) {
                        Storage::disk('public')->put($imageName, $content);
                    } else {
                        // Crea una imatge de placeholder si no hi ha connexió
                        $imageName = $this->createPlaceholderImage($imageId);
                    }
                } catch (\Exception $e) {
                    $imageName = $this->createPlaceholderImage($imageId);
                }

                // Crea el registre a la BD
                $image = Image::create([
                    'user_id'     => $user->id,
                    'image_path'  => $imageName,
                    'description' => fake()->sentence(rand(5, 20)),
                ]);

                // ── 4. Comentaris per cada imatge ─────────────────────
                // Entre 1 i 4 comentaris d'usuaris aleatoris (incloent el propietari)
                $commentCount = rand(1, 4);

                for ($j = 0; $j < $commentCount; $j++) {
                    // Selecciona un usuari aleatori per fer el comentari
                    $commenter = $allUsers->random();

                    Comment::create([
                        'user_id'  => $commenter->id,
                        'image_id' => $image->id,
                        'content'  => fake()->sentence(rand(3, 15)),
                    ]);
                }

                // ── 5. Likes per cada imatge ──────────────────────────
                // Seleccionem un subconjunt aleatori d'usuaris per fer like
                // usersWhoLike: entre 0 i tots els usuaris
                $likeCount   = rand(0, min(5, $allUsers->count()));
                $usersWhoLike = $allUsers->shuffle()->take($likeCount);

                foreach ($usersWhoLike as $liker) {
                    // Usem firstOrCreate per evitar duplicats (unique constraint)
                    Like::firstOrCreate([
                        'user_id'  => $liker->id,
                        'image_id' => $image->id,
                    ]);
                }
            }
        }

        $this->command->info('✅ Base de dades poblada correctament!');
        $this->command->info('👤 Usuari de prova: test@example.com / password');
        $this->command->info('📸 Total imatges: ' . Image::count());
        $this->command->info('💬 Total comentaris: ' . Comment::count());
        $this->command->info('❤️  Total likes: ' . Like::count());
    }

    /**
     * Crea una imatge de placeholder de color sòlid amb GD si no hi ha connexió.
     * Retorna la ruta relativa dins de storage/public.
     */
    private function createPlaceholderImage(int $seed): string
    {
        $imageName = 'images/placeholder_' . $seed . '_' . \Illuminate\Support\Str::random(6) . '.jpg';

        // Colors pastel basats en el seed
        $colors = [
            [255, 182, 193], [173, 216, 230], [144, 238, 144],
            [255, 218, 185], [221, 160, 221], [176, 224, 230],
        ];
        $color = $colors[$seed % count($colors)];

        // Crea una imatge de 800x600 amb GD
        if (function_exists('imagecreatetruecolor')) {
            $img = imagecreatetruecolor(800, 600);
            $bg  = imagecolorallocate($img, $color[0], $color[1], $color[2]);
            imagefill($img, 0, 0, $bg);

            // Afegeix text indicatiu
            $textColor = imagecolorallocate($img, 80, 80, 80);
            imagestring($img, 5, 340, 290, 'Image ' . $seed, $textColor);

            ob_start();
            imagejpeg($img, null, 85);
            $content = ob_get_clean();
            imagedestroy($img);

            Storage::disk('public')->put($imageName, $content);
        } else {
            // Si GD no està disponible, crea un fitxer JPEG mínim
            Storage::disk('public')->put($imageName, base64_decode(
                '/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8U'
            ));
        }

        return $imageName;
    }
}
