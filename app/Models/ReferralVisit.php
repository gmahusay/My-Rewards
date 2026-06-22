<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralVisit extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'referrer_id', 'ip', 'user_agent'];

    public function category()
    {
        return $this->belongsTo(ReferralCategory::class, 'category_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
