<?php

namespace Tests\Feature;

use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_link_shows_expired_page()
    {
        $link = Link::factory()->create([
            'expires_at' => Carbon::now()->subHour(),
            'status' => 'active'
        ]);

        $response = $this->get('/s/' . $link->slug);

        $response->assertStatus(200);
        $response->assertViewIs('links.expired');
        
        $link->refresh();
        $this->assertEquals('expired', $link->status);
    }

    public function test_active_link_redirects_correctly()
    {
        $link = Link::factory()->create([
            'original_url' => 'https://example.com',
            'status' => 'active',
            'expires_at' => Carbon::now()->addHour()
        ]);

        $response = $this->get('/s/' . $link->slug);

        $response->assertRedirect('https://example.com');
        
        $link->refresh();
        $this->assertEquals(1, $link->click_count);
        $this->assertEquals(1, $link->visits()->count());
    }

    public function test_inactive_link_shows_inactive_page()
    {
        $link = Link::factory()->create([
            'status' => 'inactive'
        ]);

        $response = $this->get('/s/' . $link->slug);

        $response->assertStatus(200);
        $response->assertViewIs('links.inactive');
    }

    public function test_visit_is_recorded_with_hashed_ip()
    {
        $link = Link::factory()->create([
            'status' => 'active',
            'original_url' => 'https://example.com'
        ]);

        $response = $this->get('/s/' . $link->slug);

        $visit = $link->visits()->first();
        $this->assertNotNull($visit);
        $this->assertNotNull($visit->ip_hash);
        $this->assertEquals(64, strlen($visit->ip_hash));
        $this->assertNotNull($visit->user_agent);
    }

    public function test_nonexistent_slug_returns_404()
    {
        $response = $this->get('/s/nonexistent');
        $response->assertStatus(404);
    }
}
