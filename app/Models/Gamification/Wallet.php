<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'gamification_points_wallets';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
