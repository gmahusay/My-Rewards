<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;

class CampaignProgress extends Model
{
    protected $table = 'gamification_campaign_progress';

    protected $fillable = ['participant_id', 'target_id', 'current_value', 'is_completed'];

    protected $casts = ['is_completed' => 'boolean'];

    public function participant()
    {
        return $this->belongsTo(CampaignParticipant::class, 'participant_id');
    }

    public function target()
    {
        return $this->belongsTo(CampaignTarget::class, 'target_id');
    }

    public function getPercentageAttribute(): int
    {
        if (!$this->target || $this->target->target_value <= 0) return 0;
        return min(100, (int) (($this->current_value / $this->target->target_value) * 100));
    }
}
