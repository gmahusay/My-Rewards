<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'title',
        'description',
        'location',
        'event_date',
        'points_reward',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withPivot('status', 'attended_at', 'points_awarded', 'awarded_at')
            ->withTimestamps()
            ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class)
            ->as('pivot')
            ->withCasts([
                'awarded_at' => 'datetime',
                'attended_at' => 'datetime',
            ]);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function reactions()
    {
        return $this->hasMany(EventReaction::class);
    }

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function isActive()
    {
        return $this->is_active && $this->event_date->isFuture();
    }
}
