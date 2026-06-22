<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;

class CampaignTarget extends Model
{
    protected $table = 'gamification_campaign_targets';

    protected $fillable = ['campaign_id', 'level', 'icon', 'target_type', 'product_id', 'label', 'target_value'];

    public static array $types = [
        'purchase'   => 'Purchase Product',
        'referral'   => 'Refer a User',
        'nomination' => 'Nominate Someone',
        'claim'      => 'Submit a Claim',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::$types[$this->target_type] ?? ucfirst($this->target_type);
    }

    public function getDisplayLabelAttribute(): string
    {
        if ($this->label) {
            return $this->label;
        }
        if ($this->target_type === 'purchase' && $this->product_id && $this->product) {
            return 'Buy ' . $this->product->name;
        }
        return $this->type_label;
    }
}
