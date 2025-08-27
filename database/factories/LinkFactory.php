<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'original_url' => $this->faker->url(),
            'slug' => $this->faker->unique()->lexify('??????'),
            'status' => 'active', 
            'expires_at' => null, 
            'click_count' => 0, 
        ];
    }

    public function expired()
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
            'status' => 'expired',
        ]);
    }

    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
