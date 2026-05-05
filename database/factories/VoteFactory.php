<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vote>
 */
class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'report_id' => Report::factory(),
        ];
    }
}
