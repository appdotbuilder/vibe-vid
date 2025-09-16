<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Channel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index(Request $request)
    {
        $query = Video::with(['channel.user'])
            ->published()
            ->where('visibility', 'public');

        // Filter by content type
        if ($request->get('nsfw') === 'true') {
            $query->nsfw();
        } else {
            $query->sfw();
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $videos = $query->latest()->paginate(20);

        return Inertia::render('videos/index', [
            'videos' => $videos,
            'search' => $request->get('search', ''),
            'nsfw' => $request->get('nsfw', 'false'),
        ]);
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        return Inertia::render('videos/create');
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400', // 100MB max
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_nsfw' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ]);

        // Get user's channel
        $channel = auth()->user()->channel;
        
        if (!$channel) {
            return redirect()->back()->withErrors(['channel' => 'You must create a channel first.']);
        }

        // Handle file uploads
        $videoPath = $request->file('video')->store('videos', 'public');
        $thumbnailPath = null;
        
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $video = Video::create([
            'channel_id' => $channel->id,
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $videoPath,
            'thumbnail' => $thumbnailPath,
            'is_nsfw' => $request->boolean('is_nsfw'),
            'tags' => $request->tags,
            'is_published' => true,
        ]);

        return redirect()->route('videos.show', $video)
            ->with('success', 'Video uploaded successfully!');
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video, Request $request)
    {
        $video->load(['channel.user', 'comments.user', 'comments.replies.user']);
        
        // Increment view count
        $video->increment('views_count');

        // Get related videos
        $relatedVideos = Video::with(['channel.user'])
            ->published()
            ->where('visibility', 'public')
            ->where('id', '!=', $video->id)
            ->where('is_nsfw', $video->is_nsfw)
            ->latest()
            ->limit(10)
            ->get();

        // Check if user is subscribed to this channel
        $isSubscribed = auth()->check() && 
            auth()->user()->subscriptions()->where('channel_id', $video->channel_id)->exists();

        // Get user's like status
        $userLike = null;
        if (auth()->check()) {
            /** @var \App\Models\VideoLike|null $like */
            $like = auth()->user()->videoLikes()
                ->where('video_id', $video->id)
                ->first();
            $userLike = $like ? $like->type : null;
        }

        return Inertia::render('videos/show', [
            'video' => $video,
            'relatedVideos' => $relatedVideos,
            'isSubscribed' => $isSubscribed,
            'userLike' => $userLike,
        ]);
    }

    /**
     * Show the form for editing the video.
     */
    public function edit(Video $video)
    {
        // Check if user owns this video
        if (auth()->id() !== $video->channel->user_id) {
            abort(403, 'You can only edit your own videos.');
        }

        return Inertia::render('videos/edit', [
            'video' => $video,
        ]);
    }

    /**
     * Update the specified video.
     */
    public function update(Request $request, Video $video)
    {
        // Check if user owns this video
        if (auth()->id() !== $video->channel->user_id) {
            abort(403, 'You can only update your own videos.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_nsfw' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'visibility' => 'required|in:public,unlisted,private',
        ]);

        $video->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_nsfw' => $request->boolean('is_nsfw'),
            'tags' => $request->tags,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('videos.show', $video)
            ->with('success', 'Video updated successfully!');
    }

    /**
     * Remove the specified video.
     */
    public function destroy(Video $video)
    {
        // Check if user owns this video
        if (auth()->id() !== $video->channel->user_id) {
            abort(403, 'You can only delete your own videos.');
        }

        $video->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Video deleted successfully!');
    }
}