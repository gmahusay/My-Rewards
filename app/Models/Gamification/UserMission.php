<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserMission extends Model
{
    protected $table = 'gamification_user_missions';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
}
