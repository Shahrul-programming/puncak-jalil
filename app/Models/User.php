<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Relationship: 1 User boleh ada banyak kedai
    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    // Relationship: 1 User boleh ada banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relationship: 1 User boleh buat banyak event
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relationship: 1 User boleh buat banyak forum post
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    // Relationship: 1 User boleh buat banyak forum reply
    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }

    // Relationship: 1 User boleh submit banyak report
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
