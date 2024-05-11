<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'country',
        'profile_picture',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function likedImages()
    {
        return $this->belongsToMany(Image::class)->withTimestamps();
    }

    public function ownImages()
    {
        return $this->hasMany(Image::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === "adm";
    }

    public function isModerator()
    {
        return($this->role === "adm" || $this->role === "mod");
    }

    public function commentedOn()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id', 'follows')->withTimestamps();
    }

    public function followedBy()
    {
        return $this->belongsToMany(User::class, 'user_user', 'follows', 'user_id')->withTimestamps();
    }

    public function followedBrands()
    {
        return $this->belongsToMany(Brand::class, 'brand_user')->withTimestamps();
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'to_id');
    }
}
