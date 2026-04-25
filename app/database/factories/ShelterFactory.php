<?php

namespace Database\Factories;

use App\Models\Shelter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShelterFactory extends Factory
{
    protected $model = Shelter::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->company(),
            'owner_id'    => User::factory(),
            'description' => fake()->paragraph(),
            'images'      => [],
        ];
    }
}
