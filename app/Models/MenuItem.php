<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'preparation_time',
        'is_vegetarian',
        'is_spicy',
        'is_halal',
        'customization_options'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_vegetarian' => 'boolean',
        'is_spicy' => 'boolean',
        'is_halal' => 'boolean',
        'customization_options' => 'array',
        'preparation_time' => 'integer',
    ];

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeVegetarian($query)
    {
        return $query->where('is_vegetarian', true);
    }

    public function scopeHalal($query)
    {
        return $query->where('is_halal', true);
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return 'RM ' . number_format($this->price, 2);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function isAvailable()
    {
        return $this->is_available;
    }

    public function getCategoryBadgeColorAttribute()
    {
        return match($this->category) {
            'Makanan Utama' => 'blue',
            'Minuman' => 'green',
            'Sampingan' => 'yellow',
            'Desert' => 'purple',
            default => 'gray'
        };
    }

    public function getPreparationTimeTextAttribute()
    {
        return $this->preparation_time . ' min';
    }

    // Get available categories
    public static function getCategories()
    {
        return [
            'Makanan Utama' => 'Makanan Utama',
            'Minuman' => 'Minuman',
            'Sampingan' => 'Sampingan',
            'Desert' => 'Desert'
        ];
    }

    // Check if item has customization options
    public function hasCustomizationOptions()
    {
        return !empty($this->customization_options);
    }
}
