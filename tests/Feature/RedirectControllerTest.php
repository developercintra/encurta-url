<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_original_url_if_active()
    {
        $link = Link::factory()->create([
            'original_url' => 'https://google.com',
            'status' => 'active',
            'slug' => 'abc123'
        ]);

        $response = $this->get('/s/' . $link->slug);

        $response->assertRedirect('https://google.com');
    }

    public function test_redirect_shows_expired_view_if_expired()
    {
        $link = Link::factory()->create([
            'original_url' => 'https://google.com',
            'slug' => 'xyz789',
            'expires_at' => now()->subDay(), 
        ]);

        $response = $this->get('/s/' . $link->slug);

        $response->assertStatus(200);
        $response->assertViewIs('links.expired');
        
    }

    
}
