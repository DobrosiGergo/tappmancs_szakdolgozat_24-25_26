<?php

namespace Database\Factories;

use App\Models\Specie;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecieFactory extends Factory
{
    protected $model = Specie::class;

    public function definition(): array
    {
        return ['name' => $this->faker->word()];
    }
}
