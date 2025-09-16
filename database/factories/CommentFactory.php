<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $comments = [
            'Great video! Thanks for sharing.',
            'This is exactly what I was looking for!',
            'Amazing content as always!',
            'Can you do more videos like this?',
            'Subscribed! Keep up the great work.',
            'This helped me so much, thank you!',
            'Really well explained, love it!',
            'Your content keeps getting better!',
            'First! Love your channel.',
            'This made my day, thanks!',
            'Could you make a tutorial on this topic?',
            'Incredible quality as usual!',
            'I learned something new today!',
            'Your editing skills are on point!',
            'More content like this please!',
        ];

        return [
            'video_id' => Video::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'content' => fake()->randomElement($comments),
            'likes_count' => fake()->numberBetween(0, 1000),
            'dislikes_count' => fake()->numberBetween(0, 50),
            'is_pinned' => fake()->boolean(5), // 5% chance of being pinned
        ];
    }

    /**
     * Indicate that the comment is a reply.
     */
    public function reply(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => \App\Models\Comment::factory(),
            'content' => fake()->randomElement([
                'Thanks for the reply!',
                'I agree with you on this.',
                'That makes sense, thanks!',
                'Good point!',
                'I disagree, but respect your opinion.',
                'Can you elaborate on that?',
                'Exactly what I was thinking!',
                'Thanks for clarifying!',
            ]),
        ]);
    }

    /**
     * Indicate that the comment is pinned.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
            'likes_count' => fake()->numberBetween(100, 5000),
        ]);
    }

    /**
     * Indicate that the comment is popular.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'likes_count' => fake()->numberBetween(500, 10000),
            'content' => fake()->randomElement([
                'This is the best explanation I\'ve ever seen on this topic!',
                'You just solved a problem I\'ve been struggling with for weeks!',
                'Your content quality is absolutely incredible!',
                'I\'ve been following your channel for years and this is your best work yet!',
                'Can we take a moment to appreciate how much effort went into this?',
            ]),
        ]);
    }
}