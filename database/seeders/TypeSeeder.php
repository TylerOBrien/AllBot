<?php

namespace Database\Seeders;

use App\Models\Type;

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $filepath = resource_path('data/gametypes.json');
        $types = json_decode(file_get_contents($filepath));

        foreach ($types->data as $type) {
            Type::firstOrCreate(
                [
                    'id' => $type->id,
                ],
                [
                    'name' => $type->name,
                ],
            );
        }
    }
}
