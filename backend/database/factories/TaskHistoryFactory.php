<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskHistory>
 */
class TaskHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'field_changed' => $this->faker->randomElement(['title', 'description', 'status', 'due_date']),
            'old_value' => $this->faker->word(),
            'new_value' => $this->faker->word(),
            'changed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
