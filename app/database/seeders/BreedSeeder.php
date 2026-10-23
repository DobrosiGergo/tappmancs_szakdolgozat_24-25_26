<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\Specie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BreedSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Kutya' => [
                'Keverék',
                'Ismeretlen',
                'Akita inu',
                'Alaszkai malamut',
                'Amerikai Staffordshire terrier',
                'Angol bulldog',
                'Angol cocker spániel',
                'Australian shepherd',
                'Beagle',
                'Bernáthegyi',
                'Berni pásztorkutya',
                'Bichon frisé',
                'Border collie',
                'Boxer',
                'Cane corso',
                'Chihuahua',
                'Csau csau',
                'Dalmata',
                'Dobermann',
                'Erdélyi kopó',
                'Francia bulldog',
                'Golden retriever',
                'Havanese',
                'Husky',
                'Jack Russell terrier',
                'Komondor',
                'Kuvasz',
                'Labrador retriever',
                'Magyar agár',
                'Mopsz',
                'Mudi',
                'Német dog',
                'Német juhász',
                'Pumi',
                'Puli',
                'Rottweiler',
                'Shih tzu',
                'Spitz',
                'Tacskó',
                'Uszkár',
                'Vizsla',
                'Yorkshire terrier',
            ],
            'Macska' => [
                'Keverék',
                'Ismeretlen',
                'Abesszin',
                'Bengáli',
                'Birman',
                'Brit hosszúszőrű',
                'Brit rövidszőrű',
                'Devon rex',
                'Egzotikus rövidszőrű',
                'Európai rövidszőrű',
                'Maine coon',
                'Norvég erdei macska',
                'Orosz kék',
                'Perzsa',
                'Ragdoll',
                'Skót lógófülű',
                'Sphynx',
                'Sziámi',
                'Szibériai macska',
                'Török angóra',
                'Török van',
            ],
        ];

        foreach ($data as $specieName => $breeds) {
            $specie = Specie::where('name', $specieName)->first();

            if (! $specie) {
                continue;
            }

            foreach ($breeds as $breedName) {
                Breed::updateOrCreate(
                    [
                        'name'       => $breedName,
                        'species_id' => $specie->id,
                    ],
                    [
                        'uuid' => (string) Str::uuid(),
                    ]
                );
            }
        }
    }
}
