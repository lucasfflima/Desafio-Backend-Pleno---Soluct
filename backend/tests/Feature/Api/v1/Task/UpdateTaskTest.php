<?php

namespace Tests\Feature\Api\v1\Task;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create([
            'title' => 'Título Antigo',
            'status' => 'pendente',
        ]);

        $payload = [
            'title' => 'Título Atualizado',
            'status' => 'em_andamento',
        ];

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/tasks/{$task->id}", $payload)
            ->assertOk()
            ->assertJsonFragment(['title' => 'Título Atualizado']);
    }

    public function test_user_cannot_update_task_of_another_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $payload = ['title' => 'Tentativa de atualização'];

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/tasks/{$task->id}", $payload)
            ->assertForbidden();
    }

    public function test_validation_errors_on_update(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $payload = ['status' => 'status_invalido'];

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/tasks/{$task->id}", $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
}
