<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'content', 'category', 'views_count', 'is_pinned', 'is_locked'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    // Categories available for forum posts
    public static function getCategories()
    {
        return [
            'umum' => 'Perbincangan Umum',
            'pengumuman' => 'Pengumuman',
            'soalan' => 'Soalan & Jawapan',
            'cadangan' => 'Cadangan',
            'aduan' => 'Aduan',
            'jual-beli' => 'Jual Beli',
            'aktiviti' => 'Aktiviti Komuniti',
            'keselamatan' => 'Keselamatan',
            'kemudahan' => 'Kemudahan'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class, 'post_id');
    }

    // Accessor for formatted creation date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    // Accessor for time ago
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor for category name
    public function getCategoryNameAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? 'Umum';
    }

    // Increment views count
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    // Scope for pinned posts
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // Scope for not pinned posts
    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    // Scope for unlocked posts
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    // Scope for search
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
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
}
