<?php

namespace Database\Seeders;

use App\Models\Region;

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $filepath = resource_path('data/regions.json');
        $regions = json_decode(file_get_contents($filepath));

        foreach ($regions->data as $region) {
            Region::firstOrCreate(
                [
                    'id' => $region->id,
                ],
                [
                    'name' => $region->name,
                ],
            );
        }
    }
}
