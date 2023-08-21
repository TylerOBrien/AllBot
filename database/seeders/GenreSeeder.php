<?php

namespace Database\Seeders;

use App\Models\Genre;

use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $filepath = resource_path('data/genres.json');
        $genres = json_decode(file_get_contents($filepath));

        foreach ($genres->data as $genre) {
            Genre::firstOrCreate(
                [
                    'id' => $genre->id,
                ],
                [
                    'name' => $genre->name,
                ],
            );
        }
    }
}
