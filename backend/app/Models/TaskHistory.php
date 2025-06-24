<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    public $timestamps = false; // nao usa created_at, updated_at
    protected $fillable = [
        'task_id',
        'user_id',
        'field_changed',
        'old_value',
        'new_value',
        'changed_at',
    ];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
