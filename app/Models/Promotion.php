<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id', 'title', 'description', 'start_date', 'end_date', 'is_featured'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
