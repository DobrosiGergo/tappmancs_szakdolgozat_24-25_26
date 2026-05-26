<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Specie;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('hu_HU');

        $species  = Specie::with('breeds')->get();
        $shelters = Shelter::all();

        if ($species->isEmpty() || $shelters->isEmpty()) {
            return;
        }

        $statuses = array_keys(Pet::STATUSES);
        $genders  = array_keys(Pet::GENDERS);

        foreach ($shelters as $shelter) {
            for ($i = 0; $i < 2; $i++) {
                $specie = $species->random();
                $breeds = $specie->breeds;

                if ($breeds->isEmpty()) {
                    continue;
                }

                Pet::create([
                    'uuid'         => (string) Str::uuid(),
                    'name'         => $faker->firstName(),
                    'species_id'   => $specie->id,
                    'breed_id'     => $breeds->random()->id,
                    'shelter_id'   => $shelter->id,
                    'employee_id'  => $shelter->owner_id,
                    'gender'       => $faker->randomElement($genders),
                    'status'       => $faker->randomElement($statuses),
                    'description'  => $faker->paragraphs(2, true),
                    'birth_date'   => $faker->dateTimeBetween('-7 years', '-3 months'),
                    'arrival_date' => $faker->dateTimeBetween('-2 years', 'now'),
                    'images'       => [],
                ]);
            }
        }
    }
}
