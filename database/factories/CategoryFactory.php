<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Voirie',
                'Éclairage public',
                'Propreté',
                'Sécurité routière',
                'Espaces verts',
            ]),
            'description' => fake()->sentence(10),
        ];
    }
}
