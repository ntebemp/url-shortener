<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_can_be_shortened()
    {
        $response = $this->postJson('/api/shorten', [
            'url' => 'https://openai.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'short_url',
                     'original_url',
                     'short_code'
                 ]);
    }

    public function test_shortened_url_redirects_properly()
    {
        $short = ShortUrl::create([
            'original_url' => 'https://openai.com',
            'short_code' => 'test123'
        ]);

        $response = $this->get('/test123');
        $response->assertRedirect('https://openai.com');
    }

    public function test_stats_are_returned_correctly()
    {
        $short = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'abcxyz'
        ]);

        $response = $this->getJson('/api/stats/abcxyz');

        $response->assertStatus(200)
                 ->assertJson([
                     'original_url' => 'https://example.com',
                     'short_code' => 'abcxyz',
                     'click_count' => 0,
                 ]);
    }
}
