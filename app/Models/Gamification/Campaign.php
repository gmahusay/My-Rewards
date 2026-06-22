<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = 'gamification_campaigns';

    protected $fillable = [
        'business_id', 'logo_path', 'title', 'description', 'is_active',
        'start_date', 'end_date', 'reward_points',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function targets()
    {
        return $this->hasMany(CampaignTarget::class, 'campaign_id');
    }

    public function participants()
    {
        return $this->hasMany(CampaignParticipant::class, 'campaign_id');
    }

    public function isJoinedBy(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) return 'Inactive';
        if ($this->end_date && $this->end_date->isPast()) return 'Expired';
        if ($this->start_date && $this->start_date->isFuture()) return 'Upcoming';
        return 'Active';
    }
}
