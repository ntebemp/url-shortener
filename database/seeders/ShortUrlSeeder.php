<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShortUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
 
        \App\Models\ShortUrl::create([
            'original_url' => 'https://laravel.com',
            'short_code' => 'larv01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://github.com',
            'short_code' => 'gh01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.google.com',
            'short_code' => 'goog01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.facebook.com',
            'short_code' => 'fb01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.twitter.com',
            'short_code' => 'twit01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.linkedin.com',
            'short_code' => 'link01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.youtube.com',
            'short_code' => 'yt01',
        ]);
        \App\Models\ShortUrl::create([
            'original_url' => 'https://www.instagram.com',
            'short_code' => 'insta01',
        ]);
    }
}
