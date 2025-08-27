<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_dashboard()
    {
        $user = User::factory()->create();

        Link::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'active',
            'click_count' => 5,
            'created_at' => Carbon::now()->subDays(2)
        ]);

        $this->actingAs($user);
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('totalLinks', 3);
        $response->assertViewHas('totalClicks', 15);
        $response->assertViewHas('activeLinks', 3);
        $response->assertViewHas('expiredLinks', 0);
    }

    public function test_dashboard_filters_by_period()
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
        
        $response = $this->get('/dashboard?period=1');
        $response->assertViewHas('totalLinks', 1);
        
        $response = $this->get('/dashboard?period=30');
        $response->assertViewHas('totalLinks', 2);
    }

    public function test_dashboard_shows_top_links()
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
        $response = $this->get('/dashboard');

        $response->assertViewHas('topLinks');
        $topLinks = $response->viewData('topLinks');
        $this->assertEquals(100, $topLinks->first()->click_count);
        $this->assertEquals($topLink->id, $topLinks->first()->id);
    }
}
