<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class taskinvoice extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'invoice_id',
        'created_at',
        'updated_at',
    ];
}
