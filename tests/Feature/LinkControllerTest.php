<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_links()
    {
        $response = $this->get('/links');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_list_links()
    {
        $user = User::factory()->create();
        Link::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get('/links');

        $response->assertStatus(200);
        $response->assertViewIs('links.index');
        $response->assertViewHas('links');
    }

    public function test_authenticated_user_can_create_link()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/links', [
            'original_url' => 'https://google.com'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('links.index'));

        $this->assertDatabaseHas('links', [
            'user_id' => $user->id,
            'original_url' => 'https://google.com',
            'status' => 'active'
        ]);
        
        $link = Link::where('user_id', $user->id)->first();
        $this->assertNotNull($link->slug);
        $this->assertEquals(6, strlen($link->slug));
    }

    public function test_authenticated_user_can_create_link_with_expiration()
    {
        $user = User::factory()->create();
        $expirationDate = Carbon::now()->addDays(7)->format('Y-m-d\TH:i');

        $this->actingAs($user);
        $response = $this->post('/links', [
            'original_url' => 'https://example.com',
            'expires_at' => $expirationDate
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('links', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com'
        ]);
        
        $link = Link::where('user_id', $user->id)->first();
        $this->assertNotNull($link->expires_at);
    }

    public function test_link_creation_validates_url()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $response = $this->post('/links', [
            'original_url' => 'not-a-url'
        ]);

        $response->assertSessionHasErrors('original_url');
    }

    public function test_link_creation_validates_expiration_date()
    {
        $user = User::factory()->create();
        $pastDate = Carbon::now()->subDay()->format('Y-m-d\TH:i');

        $this->actingAs($user);
        $response = $this->post('/links', [
            'original_url' => 'https://example.com',
            'expires_at' => $pastDate
        ]);

        $response->assertSessionHasErrors('expires_at');
    }

    public function test_user_can_view_their_own_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get(route('links.show', $link));

        $response->assertStatus(200);
        $response->assertViewIs('links.show');
        $response->assertViewHas('link', $link);
    }

    public function test_user_cannot_view_other_users_link()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $response = $this->get(route('links.show', $link));

        $response->assertStatus(403);
    }

    public function test_unique_slug_generation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response1 = $this->post('/links', ['original_url' => 'https://example1.com']);
        $response2 = $this->post('/links', ['original_url' => 'https://example2.com']);

        $response1->assertStatus(302);
        $response2->assertStatus(302);

        $link1 = Link::where('original_url', 'https://example1.com')->first();
        $link2 = Link::where('original_url', 'https://example2.com')->first();

        $this->assertNotEquals($link1->slug, $link2->slug);
    }
}
