<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'short_url_id',
        'clicked_at'
    ];

    public function shortUrl()
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
// This model represents a click on a shortened URL.