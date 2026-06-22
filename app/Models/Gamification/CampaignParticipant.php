<?php

namespace App\Models\Gamification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CampaignParticipant extends Model
{
    protected $table = 'gamification_campaign_participants';

    protected $fillable = ['campaign_id', 'user_id', 'is_completed', 'completed_at'];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progress()
    {
        return $this->hasMany(CampaignProgress::class, 'participant_id');
    }
}
