<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'gamification_activities_log';
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
