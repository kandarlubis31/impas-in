<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const REPUTATION_REWARD = 10;

    public static function reputationForEffort(int $effortLevel): int
    {
        return self::REPUTATION_REWARD * $effortLevel;
    }

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'effort_level',
        'base_reward',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'effort_level' => 'integer',
        'base_reward' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function acceptedOffer()
    {
        return $this->hasOne(Offer::class)->where('status', 'accepted');
    }
}