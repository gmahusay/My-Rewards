<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'referrer_id',
        'status',
        'referred_email',
        'notes',
        'rewarded_points',
    ];

    public function category()
    {
        return $this->belongsTo(ReferralCategory::class, 'category_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function business()
    {
        // Helper to access business through category
        return $this->category->business();
    }
}
