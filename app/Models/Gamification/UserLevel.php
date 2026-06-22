<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table = 'gamification_user_levels';
    protected $guarded = [];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
