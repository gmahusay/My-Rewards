<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'description',
        'proof_image_path',
        'status',
        'rewarded_points',
    ];

    public function category()
    {
        return $this->belongsTo(KpiCategory::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
