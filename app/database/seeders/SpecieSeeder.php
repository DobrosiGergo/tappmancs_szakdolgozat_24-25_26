<?php

namespace Database\Seeders;

use App\Models\Specie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecieSeeder extends Seeder
{
    public function run(): void
    {
        $species = [
            'Kutya',
            'Macska',
        ];

        foreach ($species as $name) {
            Specie::updateOrCreate(
                ['name' => $name],
                ['uuid' => (string) Str::uuid()]
            );
        }
    }
}
