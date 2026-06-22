<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $table = 'gamification_user_badges';
    protected $guarded = [];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
