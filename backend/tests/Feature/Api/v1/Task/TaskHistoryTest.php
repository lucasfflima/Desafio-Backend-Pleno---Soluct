<?php

namespace Tests\Feature\Api\v1\Task;

use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_their_task_histories(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        TaskHistory::factory()->count(3)->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/tasks/{$task->id}/history")
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_user_cannot_see_others_task_histories(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherTask = Task::factory()->for($otherUser)->create();

        TaskHistory::factory()->create([
            'task_id' => $otherTask->id,
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/tasks/{$otherTask->id}/history")
            ->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_access_task_histories(): void
    {
        $task = Task::factory()->create();
        
        $this->getJson("/api/v1/tasks/{$task->id}/history")
            ->assertUnauthorized();
    }
}
