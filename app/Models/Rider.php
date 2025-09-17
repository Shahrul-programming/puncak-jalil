<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'ic_number',
        'vehicle_type',
        'vehicle_number',
        'status',
        'availability',
        'rating',
        'total_deliveries',
        'address',
        'latitude',
        'longitude',
        'last_location_update'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrders()
    {
        return $this->orders()->whereIn('status', ['confirmed', 'preparing', 'ready']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('availability', 'available');
    }

    public function scopeByVehicleType($query, $vehicleType)
    {
        return $query->where('vehicle_type', $vehicleType);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'active' && $this->availability === 'available';
    }

    public function isBusy()
    {
        return $this->availability === 'busy';
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getFormattedPhoneAttribute()
    {
        return $this->phone;
    }

    public function getVehicleTypeTextAttribute()
    {
        return match($this->vehicle_type) {
            'motorcycle' => 'Motosikal',
            'car' => 'Kereta',
            'bicycle' => 'Basikal',
            'walking' => 'Berjalan',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'suspended' => 'Digantung',
            default => 'Tidak Diketahui'
        };
    }

    public function getAvailabilityTextAttribute()
    {
        return match($this->availability) {
            'available' => 'Tersedia',
            'busy' => 'Sibuk',
            'offline' => 'Offline',
            default => 'Tidak Diketahui'
        };
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->rating;
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

    // Update rider availability
    public function setAvailable()
    {
        $this->update(['availability' => 'available']);
    }

    public function setBusy()
    {
        $this->update(['availability' => 'busy']);
    }

    public function setOffline()
    {
        $this->update(['availability' => 'offline']);
    }

    // Update location
    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'last_location_update' => now()
        ]);
    }

    // Calculate distance from coordinates
    public function getDistanceFromCoordinates($userLat, $userLng)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Haversine formula to calculate distance
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
}
