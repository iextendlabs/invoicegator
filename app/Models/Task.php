<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\TaskLog;
use App\Models\User;

class Task extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['task_title', 'assign_to', 'date', 'created_at', 'task_status', 'payment_status'];

    // create relation of task with user(developer)
    // one task belongs to atleast one user(developer)

    public function user()
    {
        return $this->belongsTo(User::class);
        // return $this->hasOne(User::class, 'user_id', 'id');
    }

    // create relation with project
    // one task belongs to atleast one project
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    // one to many relation with tasklog
    // one task has many tasklogs
    public function tasklog()
    {
        return $this->hasMany(TaskLog::class, 'task_id', 'id');
    }

    public function invoice()
    {
        $this->hasMany(Invoice::class);
    }
}
