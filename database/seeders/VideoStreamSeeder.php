<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Channel;
use App\Models\Video;
use App\Models\Comment;
use App\Models\Subscription;
use App\Models\VideoLike;
use App\Models\CommentLike;
use Illuminate\Database\Seeder;

class VideoStreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(50)->create();

        // Create channels for most users
        $channels = [];
        foreach ($users->take(35) as $user) {
            $channels[] = Channel::factory()->create([
                'user_id' => $user->id,
            ]);
        }

        // Create some popular/verified channels
        $popularChannels = Channel::factory(10)->popular()->verified()->create([
            'user_id' => function () use ($users) {
                return $users->whereNotIn('id', Channel::pluck('user_id'))->random()->id;
            },
        ]);

        $allChannels = collect($channels)->concat($popularChannels);

        // Create videos for channels
        $videos = [];
        foreach ($allChannels as $channel) {
            $videoCount = random_int(3, 15);
            
            for ($i = 0; $i < $videoCount; $i++) {
                $video = Video::factory()->published()->create([
                    'channel_id' => $channel->id,
                    'is_nsfw' => $channel->allow_nsfw ? fake()->boolean(20) : false,
                ]);
                $videos[] = $video;
            }
        }

        // Create some trending videos
        $trendingVideos = Video::factory(20)->trending()->create([
            'channel_id' => function () use ($allChannels) {
                return $allChannels->random()->id;
            },
        ]);

        $allVideos = collect($videos)->concat($trendingVideos);

        // Create subscriptions
        foreach ($users->take(40) as $user) {
            $subscriptionCount = random_int(3, 10);
            $randomChannels = $allChannels->where('user_id', '!=', $user->id)
                                       ->random($subscriptionCount);
            
            foreach ($randomChannels as $channel) {
                Subscription::factory()->create([
                    'user_id' => $user->id,
                    'channel_id' => $channel->id,
                ]);
                
                // Update channel subscriber count
                $channel->increment('subscribers_count');
            }
        }

        // Create comments
        $comments = [];
        foreach ($allVideos->take(100) as $video) {
            $commentCount = random_int(1, 20);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $comment = Comment::factory()->create([
                    'video_id' => $video->id,
                    'user_id' => $users->random()->id,
                ]);
                $comments[] = $comment;
                
                // Update video comment count
                $video->increment('comments_count');
            }
        }

        // Create replies to some comments
        $topLevelComments = collect($comments)->take(50);
        foreach ($topLevelComments as $comment) {
            if (fake()->boolean(30)) { // 30% chance of having replies
                $replyCount = random_int(1, 5);
                
                for ($i = 0; $i < $replyCount; $i++) {
                    Comment::factory()->create([
                        'video_id' => $comment->video_id,
                        'user_id' => $users->random()->id,
                        'parent_id' => $comment->id,
                    ]);
                }
            }
        }

        // Create video likes
        foreach ($allVideos->take(80) as $video) {
            $likesCount = random_int(10, 500);
            $dislikesCount = random_int(1, 50);
            
            // Create likes
            for ($i = 0; $i < $likesCount; $i++) {
                VideoLike::factory()->like()->create([
                    'video_id' => $video->id,
                    'user_id' => $users->random()->id,
                ]);
            }
            
            // Create dislikes  
            for ($i = 0; $i < $dislikesCount; $i++) {
                VideoLike::factory()->dislike()->create([
                    'video_id' => $video->id,
                    'user_id' => $users->random()->id,
                ]);
            }
            
            // Update video counts
            $video->update([
                'likes_count' => $likesCount,
                'dislikes_count' => $dislikesCount,
            ]);
        }

        // Create comment likes
        $allComments = Comment::all();
        foreach ($allComments->take(200) as $comment) {
            $likesCount = random_int(0, 50);
            
            if ($likesCount > 0) {
                for ($i = 0; $i < $likesCount; $i++) {
                    CommentLike::factory()->like()->create([
                        'comment_id' => $comment->id,
                        'user_id' => $users->random()->id,
                    ]);
                }
                
                $comment->update(['likes_count' => $likesCount]);
            }
        }

        // Update channel video counts
        foreach ($allChannels as $channel) {
            $channel->update([
                'videos_count' => $channel->videos()->count(),
            ]);
        }

        $this->command->info('Video streaming platform seeded successfully!');
        $this->command->info('- ' . User::count() . ' users created');
        $this->command->info('- ' . Channel::count() . ' channels created');
        $this->command->info('- ' . Video::count() . ' videos created');
        $this->command->info('- ' . Comment::count() . ' comments created');
        $this->command->info('- ' . Subscription::count() . ' subscriptions created');
        $this->command->info('- ' . VideoLike::count() . ' video likes created');
        $this->command->info('- ' . CommentLike::count() . ' comment likes created');
    }
}