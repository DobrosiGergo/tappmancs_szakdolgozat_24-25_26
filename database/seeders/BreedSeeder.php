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
                'Labrador Retriever',
                'Német juhász',
                'Golden Retriever',
            ],
            'Macska' => [
                'Keverék',
                'Ismeretlen',
                'Európai rövidszőrű',
                'Maine Coon',
                'Brit rövidszőrű',
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
