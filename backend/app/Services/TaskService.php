<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function list(array $filters)
    {
        $query = Task::where('user_id', Auth::id());

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'ILIKE', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('created_at', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('created_at', '<=', $filters['date_end']);
        }

        if (!empty($filters['due_start'])) {
            $query->whereDate('due_date', '>=', $filters['due_start']);
        }

        if (!empty($filters['due_end'])) {
            $query->whereDate('due_date', '<=', $filters['due_end']);
        }

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = $filters['per_page'] ?? 10;

        return $query->orderBy($sort, $direction)->paginate($perPage);
    }

    public function create(array $data): Task
    {
        return Auth::user()->tasks()->create($data);
    }

    public function update(Task $task, array $data): Task
    {
        DB::transaction(function () use ($task, $data) {
            $this->trackChanges($task, $data);
            $task->update($data);
        });

        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }

    public function show(Task $task): Task
    {
        return $task;
    }

    protected function trackChanges(Task $task, array $data): void
    {
        foreach ($data as $field => $newValue) {
            $oldValue = $task->{$field};

            if ($oldValue != $newValue && !(is_null($oldValue) && is_null($newValue))) {
                TaskHistory::create([
                    'task_id'       => $task->id,
                    'user_id'       => Auth::id(),
                    'field_changed' => $field,
                    'old_value'     => $oldValue,
                    'new_value'     => $newValue,
                    'changed_at'    => now(),
                ]);
            }
        }
    }
}
