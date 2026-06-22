<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'total_cash',
        'total_points',
        'payment_method',
        'status',
    ];

    /**
     * Get the buyer (user) who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the business associated with the order.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get the items in the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
