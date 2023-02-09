<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Models\Invoice;

class TaskLog extends Model
{
    use HasFactory;
    protected $table = 'task_logs';
    // Mass Assignment
    protected $fillable = ['user_id', 'task_id', 'start_time', 'created_at', 'updated_at', 'end_time'];

    public $timestamps = true;

    // reverse relation with Task Log
    // TaskLog belongs to atleast one user (developer)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // single task many tasklogs 
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
