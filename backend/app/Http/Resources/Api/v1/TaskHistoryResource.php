<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'task'         => [
                'id'    => $this->task->id,
                'title' => $this->task->title,
            ],
            'user'         => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'field_changed'=> $this->field_changed,
            'old_value'    => $this->old_value,
            'new_value'    => $this->new_value,
            'changed_at'   => $this->changed_at->toDateTimeString(),
        ];
    }
}
