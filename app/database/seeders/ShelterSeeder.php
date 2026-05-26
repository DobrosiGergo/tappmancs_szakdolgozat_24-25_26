<?php

namespace Database\Seeders;

use App\Models\Shelter;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ShelterSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('hu_HU');

        $shelterOwners = User::where('type', 'Shelterowner')->get();

        foreach ($shelterOwners as $owner) {
            for ($i = 0; $i < 3; $i++) {
                Shelter::create([
                    'name'        => $faker->company,
                    'owner_id'    => $owner->id,
                    'description' => $faker->paragraphs(3, true),
                    'images'      => [],
                ]);
            }
        }
    }
}
