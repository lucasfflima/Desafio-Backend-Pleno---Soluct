<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;
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
                    'old_value'     => is_null($oldValue) ? null : (string) $oldValue,
                    'new_value'     => is_null($newValue) ? null : (string) $newValue,
                    'changed_at'    => now(),
                ]);
            }
        }
    }
}
