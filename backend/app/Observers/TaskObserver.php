<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    public function updating(Task $task)
    {
        $original = $task->getOriginal();

        foreach ($task->getDirty() as $field => $newValue) {
            $oldValue = $original[$field] ?? null;

            if ($oldValue !== $newValue) {
                TaskHistory::create([
                    'task_id'       => $task->id,
                    'user_id'       => Auth::id(),
                    'field_changed' => $field,
                    'old_value'     => $this->convertValueToString($oldValue),
                    'new_value'     => $this->convertValueToString($newValue),
                    'changed_at'    => now(),
                ]);
            }
        }
    }

    private function convertValueToString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof TaskStatus) {
            return $value->value;
        }

        return (string) $value;
    }
}
