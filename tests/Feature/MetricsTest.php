<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_metrics_summary()
    {
        $user = User::factory()->create();
        Link::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'active',
            'click_count' => 5,
            'created_at' => Carbon::now()->subDays(2)
        ]);
        Link::factory()->create([
            'user_id' => $user->id,
            'status' => 'expired',
            'click_count' => 10,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        $this->actingAs($user);
        $response = $this->get('/metrics/summary?period=7');

        $response->assertStatus(200);
        $response->assertJson([
            'total_links' => 4,
            'active_links' => 3,
            'expired_links' => 1,
            'inactive_links' => 0,
            'total_clicks' => 25,
            'period' => '7'
        ]);
    }

    public function test_authenticated_user_can_get_top_links()
    {
        $user = User::factory()->create();
        $topLink = Link::factory()->create([
            'user_id' => $user->id,
            'click_count' => 100,
            'created_at' => Carbon::now()->subDays(2)
        ]);
        Link::factory()->create([
            'user_id' => $user->id,
            'click_count' => 50,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        $this->actingAs($user);
        $response = $this->get('/metrics/top?period=7&limit=5');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'links' => [
                '*' => ['id', 'original_url', 'slug', 'click_count', 'status', 'created_at']
            ],
            'period'
        ]);
        
        $data = $response->json();
        $this->assertEquals(100, $data['links'][0]['click_count']);
        $this->assertEquals($topLink->slug, $data['links'][0]['slug']);
    }

    public function test_metrics_filters_by_period_correctly()
    {
        $user = User::factory()->create();
        
        Link::factory()->create([
            'user_id' => $user->id,
            'created_at' => Carbon::today()
        ]);
        
        Link::factory()->create([
            'user_id' => $user->id,
            'created_at' => Carbon::now()->subDays(10)
        ]);

        $this->actingAs($user);
        
        $response = $this->get('/metrics/summary?period=1');
        $response->assertJson(['total_links' => 1]);

        $response = $this->get('/metrics/summary?period=30');
        $response->assertJson(['total_links' => 2]);
    }

    public function test_guest_cannot_access_metrics()
    {
        $response = $this->get('/metrics/summary');
        $response->assertRedirect('/login');

        $response = $this->get('/metrics/top');
        $response->assertRedirect('/login');
    }
}
