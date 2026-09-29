<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory; 

    protected $fillable = [
        'task_id',
        'helper_id',
        'conditions',
        'extra_fee',
        'status',
    ];

    protected $casts = [
        'conditions' => 'array',
        'extra_fee' => 'float',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }
}