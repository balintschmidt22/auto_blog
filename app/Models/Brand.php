<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'image',
    ];

    public function types()
    {
        return $this->hasMany(Type::class, 'brand_id');
    }

    public function followedBy()
    {
        return $this->belongsToMany(User::class, 'brand_user')->withTimestamps();
    }
}
