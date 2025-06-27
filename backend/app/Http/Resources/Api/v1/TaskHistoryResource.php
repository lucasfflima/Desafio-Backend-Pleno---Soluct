<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'task_id'        => $this->task_id,
            'task_title'     => $this->whenLoaded('task', fn () => $this->task->title),
            'user_id'        => $this->user_id,
            'user_name'      => $this->whenLoaded('user', fn () => $this->user->name),
            'user_email'     => $this->whenLoaded('user', fn () => $this->user->email),
            'field_changed'  => $this->field_changed,
            'old_value'      => $this->old_value,
            'new_value'      => $this->new_value,
            'changed_at'     => $this->changed_at->toIso8601String(),
        ];
    }
}
