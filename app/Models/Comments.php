<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'content',
        'status',
    ];

    protected $dates = [
        'comment_create',
    ];

    // Relación con la tabla 'tasks'
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    // Relación con la tabla 'users'
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
