<?php

namespace App\Models;

use App\Models\User;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public $timestamps = true;


    protected $fillable = [
        'project_name',
        'project_desc',
        'project_status',
        'project_rate',
        'per_hour_rate',
        // 'user_id',
        'created_at',
        'updated_at',
    ];

    // one project is belongs to atleast one user (developer)
    // single project has many user (developers)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // create relation with task
    // project has many task
    public function task()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }
}
