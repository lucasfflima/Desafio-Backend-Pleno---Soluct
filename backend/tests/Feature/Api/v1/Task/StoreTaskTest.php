<?php

namespace Tests\Feature\Api\v1\Task;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
            'status' => 'pendente',
            'due_date' => now()->addDays(3)->toDateString(),
        ];

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/tasks', $payload)
            ->assertCreated()
            ->assertJsonFragment(['title' => 'Nova Tarefa']);
    }

    public function test_unauthenticated_user_cannot_create_task(): void
    {
        $payload = [
            'title' => 'Tarefa não autorizada',
            'status' => 'pendente',
        ];

        $this->postJson('/api/v1/tasks', $payload)
            ->assertUnauthorized();
    }

    public function test_invalid_payload_returns_validation_errors(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/tasks', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }
}
