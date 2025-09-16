<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'channel_id' => Channel::factory(),
            'notifications_enabled' => fake()->boolean(80), // 80% chance of notifications enabled
        ];
    }

    /**
     * Indicate that notifications are disabled.
     */
    public function withoutNotifications(): static
    {
        return $this->state(fn (array $attributes) => [
            'notifications_enabled' => false,
        ]);
    }
}