<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'price_cash',
        'price_points',
        'stock_quantity',
        'image_path',
        'is_active',
    ];

    /**
     * Get the business that owns the product.
     */
    public function business()
    {
        return $this->belongsTo(User::class, 'business_id');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
