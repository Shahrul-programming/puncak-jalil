<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'item_name',
        'quantity',
        'unit_price',
        'total_price',
        'special_requests'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->unit_price, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return 'RM ' . number_format($this->total_price, 2);
    }

    public function hasSpecialRequests()
    {
        return !empty($this->special_requests);
    }
}
