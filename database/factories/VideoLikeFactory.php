<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoLike>
 */
class VideoLikeFactory extends Factory
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
            'video_id' => Video::factory(),
            'type' => fake()->randomElement(['like', 'dislike']),
        ];
    }

    /**
     * Indicate that this is a like.
     */
    public function like(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'like',
        ]);
    }

    /**
     * Indicate that this is a dislike.
     */
    public function dislike(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'dislike',
        ]);
    }
}