<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        return [
            'subject'    => $this->faker->sentence(3),
            'message'    => $this->faker->paragraph(),
            'user_id'    => User::factory(),
            'shelter_id' => Shelter::factory(),
            'pet_id'     => Pet::factory(),
        ];
    }
}
