<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category', 'description', 'location', 'image', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Report categories
    public static function getCategories()
    {
        return [
            'infrastruktur' => 'Infrastruktur',
            'keselamatan' => 'Keselamatan',
            'kebersihan' => 'Kebersihan',
            'lampu-jalan' => 'Lampu Jalan',
            'jalan-raya' => 'Jalan Raya',
            'longkang' => 'Longkang & Parit',
            'taman' => 'Taman & Landskap',
            'kemudahan-awam' => 'Kemudahan Awam',
            'bunyi-bising' => 'Bunyi Bising',
            'haiwan-terbiar' => 'Haiwan Terbiar',
            'lain-lain' => 'Lain-lain'
        ];
    }

    // Status options
    public static function getStatuses()
    {
        return [
            'open' => 'Baru',
            'in_progress' => 'Dalam Tindakan',
            'resolved' => 'Selesai'
        ];
    }

    // Priority levels
    public static function getPriorities()
    {
        return [
            'low' => 'Rendah',
            'medium' => 'Sederhana', 
            'high' => 'Tinggi',
            'urgent' => 'Kecemasan'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for category name
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? 'Tidak Diketahui';
    }

    // Accessor for status name
    public function getStatusNameAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Tidak Diketahui';
    }

    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    // Accessor for time ago
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor for status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'resolved' => 'green',
            default => 'gray'
        };
    }

    // Accessor for status icon
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'open' => 'fas fa-exclamation-circle',
            'in_progress' => 'fas fa-clock',
            'resolved' => 'fas fa-check-circle',
            default => 'fas fa-question-circle'
        };
    }

    // Scope for search
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
        });
    }

    // Scope for category filter
    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'semua') {
            return $query->where('category', $category);
        }
        return $query;
    }

    // Scope for status filter
    public function scopeStatus($query, $status)
    {
        if ($status && $status !== 'semua') {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope for open reports
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // Scope for in progress reports
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Scope for resolved reports
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // Scope for recent reports (last 30 days)
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }
}
