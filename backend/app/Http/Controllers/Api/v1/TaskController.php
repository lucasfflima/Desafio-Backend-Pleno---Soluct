<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\StoreTaskRequest;
use App\Http\Requests\Api\v1\UpdateTaskRequest;
use App\Http\Resources\Api\v1\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);
        $tasks = $this->taskService->list($request->all());

        return response()->json([
            'data' => TaskResource::collection($tasks)
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);
        $task = $this->taskService->create($request->validated());

        return response()->json([
            'message' => 'Tarefa criada com sucesso.',
            'task'    => new TaskResource($task),
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return response()->json([
            'data' => new TaskResource($task)
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $updatedTask = $this->taskService->update($task, $request->validated());

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso.',
            'task'    => new TaskResource($updatedTask),
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }
}
