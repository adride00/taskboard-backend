<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'creation_task',
        'deadline',
        'priority',
        'status',
    ];

    protected $casts = [
        'creation_task' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
