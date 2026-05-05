<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'latitude' => fake()->randomFloat(7, 30.0, 35.0),
            'longitude' => fake()->randomFloat(7, -9.8, -1.0),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'resolved']),
        ];
    }
}
