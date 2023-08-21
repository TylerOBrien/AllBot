<?php

namespace Database\Seeders;

use App\Models\Platform;

use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $filepath = resource_path('data/platforms.json');
        $platforms = json_decode(file_get_contents($filepath));

        foreach ($platforms->data as $platform) {
            Platform::firstOrCreate(
                [
                    'id' => $platform->id,
                ],
                [
                    'name' => $platform->name,
                    'release_year' => ($platform->released > 999 && $platform->released <= now()->year) ? $platform->released : null,
                ],
            );
        }
    }
}
