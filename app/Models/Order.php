<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'shop_id',
        'rider_id',
        'subtotal',
        'delivery_fee',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'delivery_address',
        'special_instructions',
        'requested_delivery_time',
        'estimated_delivery_time',
        'delivered_at'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'requested_delivery_time' => 'datetime',
        'estimated_delivery_time' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    // Boot method to generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    // Generate unique order number
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPreparing()
    {
        return $this->status === 'preparing';
    }

    public function isReady()
    {
        return $this->status === 'ready';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'orange',
            'ready' => 'green',
            'delivered' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pengesahan',
            'confirmed' => 'Disahkan',
            'preparing' => 'Sedang Disediakan',
            'ready' => 'Siap Diambil',
            'delivered' => 'Dihantar',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    // Rider assignment methods
    public function hasRider()
    {
        return !is_null($this->rider_id);
    }

    public function canAssignRider()
    {
        return in_array($this->status, ['confirmed', 'preparing', 'ready']) && !$this->hasRider();
    }

    public function canUnassignRider()
    {
        return $this->hasRider() && !in_array($this->status, ['out_for_delivery', 'delivered']);
    }

    public function assignRider(Rider $rider)
    {
        if (!$this->canAssignRider()) {
            return false;
        }

        $this->update([
            'rider_id' => $rider->id,
            'status' => 'ready' // Update status to ready when rider is assigned
        ]);

        // Update rider availability
        $rider->setBusy();

        return true;
    }

    public function unassignRider()
    {
        if (!$this->canUnassignRider()) {
            return false;
        }

        $rider = $this->rider;
        $this->update(['rider_id' => null]);

        // Update rider availability if they have no other active orders
        if ($rider && $rider->activeOrders()->count() === 0) {
            $rider->setAvailable();
        }

        return true;
    }

    // Calculate estimated delivery time
    public function calculateEstimatedDeliveryTime()
    {
        $preparationTime = $this->orderItems->sum(function ($item) {
            return $item->menuItem->preparation_time ?? 15;
        });

        $totalTime = $preparationTime + 30; // Add 30 minutes for delivery
        return now()->addMinutes($totalTime);
    }
}
