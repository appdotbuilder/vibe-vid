<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Amazing Sunset Timelapse',
            'Cooking Tutorial: Perfect Pasta',
            'Gaming Session Highlights',
            'Travel Vlog: Tokyo Adventure',
            'DIY Home Improvement Tips',
            'Workout Routine for Beginners',
            'Product Review: Latest Tech',
            'Music Cover Performance',
            'Art Tutorial: Digital Painting',
            'Comedy Sketch: Daily Life',
            'Educational: Science Explained',
            'Fashion Haul and Styling',
            'Pet Training Tips',
            'Book Review and Discussion',
            'Photography Tips for Beginners',
        ];

        return [
            'channel_id' => Channel::factory(),
            'title' => fake()->randomElement($titles) . ' - ' . fake()->words(2, true),
            'description' => fake()->paragraph(random_int(3, 6)),
            'video_path' => 'videos/sample-' . fake()->uuid() . '.mp4',
            'thumbnail' => fake()->boolean(80) ? 'thumbnails/thumb-' . fake()->uuid() . '.jpg' : null,
            'duration' => fake()->numberBetween(60, 3600), // 1 minute to 1 hour
            'views_count' => fake()->numberBetween(0, 1000000),
            'likes_count' => fake()->numberBetween(0, 50000),
            'dislikes_count' => fake()->numberBetween(0, 5000),
            'comments_count' => fake()->numberBetween(0, 1000),
            'is_nsfw' => fake()->boolean(15), // 15% chance of being NSFW
            'is_published' => fake()->boolean(90), // 90% chance of being published
            'visibility' => fake()->randomElement(['public', 'unlisted', 'private']),
            'tags' => fake()->words(random_int(3, 8)),
        ];
    }

    /**
     * Indicate that the video is NSFW.
     */
    public function nsfw(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_nsfw' => true,
        ]);
    }

    /**
     * Indicate that the video is SFW.
     */
    public function sfw(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_nsfw' => false,
        ]);
    }

    /**
     * Indicate that the video is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'visibility' => 'public',
        ]);
    }

    /**
     * Indicate that the video is popular.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(100000, 5000000),
            'likes_count' => fake()->numberBetween(5000, 200000),
            'comments_count' => fake()->numberBetween(100, 5000),
            'is_published' => true,
            'visibility' => 'public',
        ]);
    }

    /**
     * Indicate that the video is trending.
     */
    public function trending(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(10000, 1000000),
            'likes_count' => fake()->numberBetween(500, 50000),
            'comments_count' => fake()->numberBetween(50, 2000),
            'is_published' => true,
            'visibility' => 'public',
            'created_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}