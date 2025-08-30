<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'category', 'description', 'address',
        'phone', 'whatsapp', 'website', 'opening_hours', 
        'latitude', 'longitude', 'status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    // Helper methods
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getFormattedWhatsappLinkAttribute()
    {
        if ($this->whatsapp) {
            // Format WhatsApp number to link
            $cleanWhatsapp = preg_replace('/[^0-9]/', '', $this->whatsapp);
            
            // Ensure it starts with country code
            if (substr($cleanWhatsapp, 0, 2) !== '60') {
                if (substr($cleanWhatsapp, 0, 1) === '0') {
                    $cleanWhatsapp = '6' . $cleanWhatsapp; // Convert 0123456789 to 60123456789
                } else {
                    $cleanWhatsapp = '60' . $cleanWhatsapp; // Add Malaysia country code
                }
            }
            
            return "https://wa.me/{$cleanWhatsapp}";
        }
        
        if ($this->phone) {
            // Fallback to phone if WhatsApp not available
            $cleanPhone = preg_replace('/[^0-9]/', '', $this->phone);
            if (substr($cleanPhone, 0, 1) === '0') {
                $cleanPhone = '6' . $cleanPhone; // Malaysia country code
            }
            return "https://wa.me/{$cleanPhone}";
        }
        
        return null;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");
        });
    }
}
