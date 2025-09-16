<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home page with video feed.
     */
    public function index(Request $request)
    {
        $query = Video::with(['channel.user'])
            ->published()
            ->where('visibility', 'public');

        // Content filter
        $contentFilter = $request->get('content', 'sfw');
        if ($contentFilter === 'nsfw') {
            $query->nsfw();
        } else {
            $query->sfw();
        }

        // Search functionality
        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('channel', function ($channelQuery) use ($search) {
                      $channelQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'trending':
                $query->where('created_at', '>=', now()->subDays(7))
                      ->orderBy('views_count', 'desc');
                break;
            case 'liked':
                $query->orderBy('likes_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $videos = $query->paginate(24);

        // Get trending channels
        $trendingChannels = Channel::with(['user'])
            ->withCount('videos')
            ->orderBy('subscribers_count', 'desc')
            ->limit(8)
            ->get();

        // Get video statistics
        $stats = [
            'total_videos' => Video::published()->count(),
            'sfw_videos' => Video::published()->sfw()->count(),
            'nsfw_videos' => Video::published()->nsfw()->count(),
            'total_channels' => Channel::count(),
        ];

        return Inertia::render('welcome', [
            'videos' => $videos,
            'trendingChannels' => $trendingChannels,
            'stats' => $stats,
            'filters' => [
                'content' => $contentFilter,
                'search' => $request->get('search', ''),
                'sort' => $sort,
            ],
        ]);
    }
}