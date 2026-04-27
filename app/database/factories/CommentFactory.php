<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(),
            'pet_id'  => Pet::factory(),
            'user_id' => User::factory(),
        ];
    }
}
