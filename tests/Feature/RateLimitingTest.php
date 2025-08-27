<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_links_within_rate_limit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/links', [
                'original_url' => "https://example{$i}.com"
            ]);
            $response->assertStatus(302);
        }

        $this->assertEquals(5, $user->links()->count());
    }

    public function test_rate_limit_prevents_excessive_link_creation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        for ($i = 0; $i < 35; $i++) {
            $response = $this->post('/links', [
                'original_url' => "https://example{$i}.com"
            ]);
            
            if ($i < 30) {
                $response->assertStatus(302);
            } else {
                $response->assertStatus(429);
            }
        }

        $this->assertEquals(30, $user->links()->count());
    }

    public function test_rate_limit_is_per_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);
        for ($i = 0; $i < 30; $i++) {
            $response = $this->post('/links', [
                'original_url' => "https://user1-example{$i}.com"
            ]);
            $response->assertStatus(302);
        }

        $this->actingAs($user2);
        $response = $this->post('/links', [
            'original_url' => 'https://user2-example.com'
        ]);
        $response->assertStatus(302);

        $this->assertEquals(30, $user1->links()->count());
        $this->assertEquals(1, $user2->links()->count());
    }
}
