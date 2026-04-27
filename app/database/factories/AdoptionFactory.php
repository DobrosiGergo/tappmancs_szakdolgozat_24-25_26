<?php

namespace Database\Factories;

use App\Models\Adoption;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdoptionFactory extends Factory
{
    protected $model = Adoption::class;

    public function definition(): array
    {
        return [
            'pet_id'  => Pet::factory(),
            'user_id' => User::factory(),
        ];
    }
}
