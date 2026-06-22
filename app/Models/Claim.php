<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'category_id',
        'title',
        'description',
        'amount',
        'rewarded_points',
        'invoice_number',
        'store_name',
        'status',
        'document_path',
        'admin_notes',
    ];

    /**
     * Get the customer who submitted the claim.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business responsible for the claim.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get the category of the claim.
     */
    public function category()
    {
        return $this->belongsTo(ClaimCategory::class, 'category_id');
    }
}
