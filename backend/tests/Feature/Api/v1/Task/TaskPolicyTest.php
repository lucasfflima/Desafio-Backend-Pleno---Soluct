<?php

namespace Tests\Feature\Api\v1\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/tasks/{$task->id}")
            ->assertOk()
            ->assertJson(['data' => ['id' => $task->id]]);
    }

    public function test_user_cannot_view_others_task(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user, 'sanctum')
            ->getJson("/api/v1/tasks/{$task->id}")
            ->assertForbidden();
    }

    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/tasks/{$task->id}", [
                'title'       => 'Atualizado',
                'description' => 'Desc atualizada',
                'status'      => 'pendente',
                'due_date'    => now()->addDay()->toDateString(),
            ])
            ->assertOk()
            ->assertJsonFragment(['title' => 'Atualizado']);
    }

    public function test_user_cannot_update_others_task(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/tasks/{$task->id}", [
                'title'       => 'Hacker!',
                'description' => 'Tentativa!',
                'status'      => 'pendente',
                'due_date'    => now()->addDay()->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/tasks/{$task->id}")
            ->assertOk();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_others_task(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/tasks/{$task->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
}
