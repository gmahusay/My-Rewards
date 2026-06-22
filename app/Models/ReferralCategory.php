<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'image_path',
        'referral_link',
        'points_reward',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'category_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'referral_category_participants', 'category_id', 'user_id')
            ->withTimestamps();
    }

    public function hasJoinedBy(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
