<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_generate_qr_code_for_their_link()
    {
        $user = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get(route('links.qrcode', $link));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/svg+xml');
    }

    public function test_user_cannot_generate_qr_code_for_other_users_link()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $response = $this->get(route('links.qrcode', $link));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_generate_qr_code()
    {
        $link = Link::factory()->create();

        $response = $this->get(route('links.qrcode', $link));

        $response->assertRedirect('/login');
    }
}
