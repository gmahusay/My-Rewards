<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'points_reward',
        'image_path',
        'description',
        'is_active',
        'end_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'end_date' => 'date',
    ];

    /**
     * Get the business that owns this category.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get the claims in this category.
     */
    public function claims()
    {
        return $this->hasMany(Claim::class, 'category_id');
    }
}
