<?php

namespace Database\Factories\SalaryHistory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\SalaryHistory\Infrastructure\EloquentModels\SalaryHistoryEloquentModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SalaryHistoryEloquentModelFactory extends Factory
{

    protected $model = SalaryHistoryEloquentModel::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 100),
            'on_date' => fake()->date(),
            'salary' => fake()->numberBetween(0, 100000),
            'currency' => fake()->randomElement(['USD', 'VND', 'JPY']),
            'note' => fake()->sentence(),
        ];
    }

}
