<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
     protected $fillable = [
        'original_url',
        'short_code',
        'expires_at'
    ];

    protected $dates = ['expires_at'];

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
// This model represents a shortened URL and its associated properties.
