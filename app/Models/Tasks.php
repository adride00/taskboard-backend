<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        "project_id",
        "title",
        "description",
        "creation_task",
        "due_task",
        "priority",
        "status"
    ];
}
