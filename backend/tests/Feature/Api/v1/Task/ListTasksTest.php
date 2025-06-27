<?php

namespace Tests\Feature\Api\v1\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_own_tasks(): void
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(5)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/tasks')
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    public function test_user_cannot_see_tasks_of_others(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(); // de outros usuários

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/tasks')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_filters_work_correctly(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Important Task',
            'status' => 'pendente'
        ]);
        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Other Task',
            'status' => 'concluida'
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/tasks?status=pendente&title=important')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}
