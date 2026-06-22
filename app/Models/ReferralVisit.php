<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralVisit extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'referrer_id', 'ip', 'user_agent', 'referer_url'];

    /**
     * Extract a readable domain/source from the referer URL.
     */
    public function getRefererDomainAttribute(): string
    {
        if (!$this->referer_url) return '—';
        $host = parse_url($this->referer_url, PHP_URL_HOST);
        return $host ?: $this->referer_url;
    }

    public function category()
    {
        return $this->belongsTo(ReferralCategory::class, 'category_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }
}
