<?php

namespace Database\Seeders;

use App\Models\Shelter;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ShelterSeeder extends Seeder
{
    private array $cities = [
        'Budapest', 'Debrecen', 'Miskolc', 'Pécs', 'Győr',
        'Nyíregyháza', 'Kecskemét', 'Székesfehérvár', 'Szombathely',
        'Szolnok', 'Érd', 'Tatabánya', 'Kaposvár', 'Veszprém',
        'Zalaegerszeg', 'Sopron', 'Eger', 'Nagykanizsa', 'Dunakeszi',
    ];

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
                    'location'    => $this->cities[array_rand($this->cities)],
                    'images'      => [],
                ]);
            }
        }
    }
}
