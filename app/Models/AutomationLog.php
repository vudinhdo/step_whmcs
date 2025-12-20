<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    protected $fillable = [
        'type',
        'description',
        'status',
        'run_at',
    ];

    protected $casts = [
        'run_at' => 'datetime',
    ];
}
