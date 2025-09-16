<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Channel>
 */
class ChannelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(random_int(2, 3), true);
        
        return [
            'user_id' => User::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(random_int(2, 4)),
            'subscribers_count' => fake()->numberBetween(0, 100000),
            'videos_count' => fake()->numberBetween(0, 50),
            'is_verified' => fake()->boolean(10), // 10% chance of being verified
            'allow_nsfw' => fake()->boolean(30), // 30% chance of allowing NSFW
        ];
    }

    /**
     * Indicate that the channel is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'subscribers_count' => fake()->numberBetween(10000, 1000000),
        ]);
    }

    /**
     * Indicate that the channel allows NSFW content.
     */
    public function nsfw(): static
    {
        return $this->state(fn (array $attributes) => [
            'allow_nsfw' => true,
        ]);
    }

    /**
     * Indicate that the channel is popular.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscribers_count' => fake()->numberBetween(50000, 500000),
            'videos_count' => fake()->numberBetween(20, 100),
            'is_verified' => fake()->boolean(70),
        ]);
    }
}