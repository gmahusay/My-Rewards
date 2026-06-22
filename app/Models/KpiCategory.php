<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'image_path',
        'points_reward',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class, 'category_id');
    }
}
