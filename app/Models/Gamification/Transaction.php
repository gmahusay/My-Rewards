<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'gamification_transactions';
    protected $guarded = [];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
