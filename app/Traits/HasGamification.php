<?php

namespace App\Traits;

use App\Models\Gamification\Wallet;
use App\Models\Gamification\UserBadge;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserLevel;
use App\Models\Gamification\UserMission;

trait HasGamification
{
    public function gamificationWallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'gamification_user_badges', 'user_id', 'badge_id')->withPivot('awarded_at');
    }

    public function level()
    {
        return $this->hasOne(UserLevel::class);
    }

    public function missions()
    {
        return $this->hasMany(UserMission::class);
    }
}
