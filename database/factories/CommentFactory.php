<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'report_id' => Report::factory(),
            'content' => fake()->sentence(12),
            'is_admin' => false,
        ];
    }
}
