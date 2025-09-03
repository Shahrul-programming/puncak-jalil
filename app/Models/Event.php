<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'date', 'location', 'type'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d M Y, g:i A');
    }

    // Get date for display (e.g., "Today", "Tomorrow", or date)
    public function getDisplayDateAttribute()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        if ($this->date->isSameDay($today)) {
            return 'Hari Ini, ' . $this->date->format('g:i A');
        } elseif ($this->date->isSameDay($tomorrow)) {
            return 'Esok, ' . $this->date->format('g:i A');
        } else {
            return $this->date->format('d M Y, g:i A');
        }
    }

    // Check if event is upcoming
    public function getIsUpcomingAttribute()
    {
        return $this->date >= Carbon::now();
    }

    // Check if event is today
    public function getIsTodayAttribute()
    {
        return $this->date->isSameDay(Carbon::today());
    }

    // Get time until event
    public function getTimeUntilAttribute()
    {
        return $this->date->diffForHumans();
    }

    // Get events for today
    public static function today()
    {
        return static::whereDate('date', Carbon::today());
    }

    // Get upcoming events
    public static function upcoming()
    {
        return static::where('date', '>=', Carbon::now());
    }

    // Get past events
    public static function past()
    {
        return static::where('date', '<', Carbon::now());
    }

    // Scope for event type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
