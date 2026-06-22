<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'nominator_id',
        'nominee_id',
        'reason',
    ];

    public function category()
    {
        return $this->belongsTo(NominationCategory::class, 'category_id');
    }

    public function nominator()
    {
        return $this->belongsTo(User::class, 'nominator_id');
    }

    public function nominee()
    {
        return $this->belongsTo(User::class, 'nominee_id');
    }
}
