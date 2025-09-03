<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'category', 'description', 'image', 'address',
        'phone', 'whatsapp', 'website', 'opening_hours', 
        'latitude', 'longitude', 'status'
    ];

    protected $casts = [
        'latitude'   => 'decimal:8',
        'longitude'  => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->average_rating;
        $stars = [];
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars[] = 'full';
            } elseif ($i - 0.5 <= $rating) {
                $stars[] = 'half';
            } else {
                $stars[] = 'empty';
            }
        }
        
        return $stars;
    }

    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        $totalReviews = $this->review_count;
        
        for ($i = 1; $i <= 5; $i++) {
            $count = $this->reviews()->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100, 1) : 0;
            
            $distribution[$i] = [
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        
        return $distribution;
    }

    public function hasUserReviewed($userId)
    {
        return $this->reviews()->where('user_id', $userId)->exists();
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

    // Distance calculation method
    public function getDistanceFromCoordinates($userLat, $userLng)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Haversine formula to calculate distance between two points
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($this->latitude - $userLat);
        $dLng = deg2rad($this->longitude - $userLng);

        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($userLat)) * cos(deg2rad($this->latitude)) * 
             sin($dLng/2) * sin($dLng/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    // Scope for nearby shops
    public function scopeNearby($query, $userLat, $userLng, $radiusKm = 10)
    {
        if (!$userLat || !$userLng) {
            return $query;
        }

        return $query->whereRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
            [$userLat, $userLng, $userLat, $radiusKm]
        );
    }
}
