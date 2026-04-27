<?php

namespace Database\Seeders;

use App\Models\Shelter;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ShelterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $shelterOwners = User::where('type', 'Shelterowner')->get();

        foreach ($shelterOwners as $owner) {

            $numShelters = rand(1, 3);

            for ($i = 0; $i < $numShelters; $i++) {
                Shelter::create([
                    'name'        => $faker->company . ' Shelter',
                    'owner_id'    => $owner->id,
                    'description' => $faker->paragraph(3),
                    'images'      => [],
                ]);
            }
        }
    }
}
