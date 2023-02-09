<?php

namespace App\Models;

use Illuminate\Support\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = true;
    use HasFactory;

    protected $fillable = [
        'project_name',
        'date_created',
        'created_at',
        'updated_at',
        'invoice_title',
        'start_date',
        'end_date',
        'task_id',
        'total_hours',
        'invoice_rate',
    ];

    public function task()
    {
        $this->belongsTo(Task::class);
    }
}
