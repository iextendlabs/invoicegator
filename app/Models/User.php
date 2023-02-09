<?php

namespace App\Models;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'address_line_one',
        'address_line_two',
        'country',
        'created_at',
        'user_role',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // create relation with Project Table (atleast one project is belongsTo one user -> developer)
    public function project()
    {
        return $this->hasOne(Project::class);
    }

    // create relation with task table
    // user(developer) is assigned many tasks
    public function task()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
        // return $this->belongsTo(Task::class, 'task_id', 'id');
    }

    // one to many relation with tasklogs
    public function tasklog()
    {
        return $this->hasMany(TaskLog::class, 'user_id', 'id');
    }
}
