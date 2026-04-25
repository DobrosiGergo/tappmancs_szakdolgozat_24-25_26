<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition(): array
    {
        return [
            'pet_id'  => Pet::factory(),
            'user_id' => User::factory(),
        ];
    }
}
