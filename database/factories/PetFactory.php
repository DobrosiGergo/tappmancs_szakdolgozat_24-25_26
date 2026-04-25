<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\Specie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->firstName(),
            'birth_date'   => $this->faker->dateTimeBetween('-15 years', 'now')->format('Y-m-d'),
            'arrival_date' => now(),
            'employee_id'  => User::factory(),
            'shelter_id'   => Shelter::factory(),
            'species_id'   => Specie::factory(),
            'breed_id'     => Breed::factory(),
            'gender'       => $this->faker->randomElement(['male', 'female', 'unknown']),
            'status'       => 'free',
            'description'  => $this->faker->sentence(10),
            'images'       => [],
        ];
    }
}
