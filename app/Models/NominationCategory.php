<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NominationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'image_path',
        'start_date',
        'end_date',
        'points_reward',
        'winner_id',
        'awarded_at',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'awarded_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function nominations()
    {
        return $this->hasMany(Nomination::class, 'category_id');
    }

    public function isActive()
    {
        $today = now()->startOfDay();
        return $this->is_active && $today->between($this->start_date, $this->end_date);
    }
}
