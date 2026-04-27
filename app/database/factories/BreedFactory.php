<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Specie;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreedFactory extends Factory
{
    protected $model = Breed::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->word(),
            'species_id' => function () {
                return Specie::factory()->create()->id;
            },
        ];
    }
}
