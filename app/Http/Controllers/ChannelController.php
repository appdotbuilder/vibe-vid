<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ChannelController extends Controller
{
    /**
     * Display a listing of channels.
     */
    public function index(Request $request)
    {
        $query = Channel::with(['user'])
            ->withCount(['videos', 'subscriptions']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $channels = $query->orderBy('subscribers_count', 'desc')
                         ->paginate(20);

        return Inertia::render('channels/index', [
            'channels' => $channels,
            'search' => $request->get('search', ''),
        ]);
    }

    /**
     * Show the form for creating a new channel.
     */
    public function create()
    {
        // Check if user already has a channel
        if (auth()->user()->channel) {
            return redirect()->route('channels.show', auth()->user()->channel->slug);
        }

        return Inertia::render('channels/create');
    }

    /**
     * Store a newly created channel.
     */
    public function store(Request $request)
    {
        // Check if user already has a channel
        if (auth()->user()->channel) {
            return redirect()->route('channels.show', auth()->user()->channel->slug)
                ->withErrors(['channel' => 'You already have a channel.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'allow_nsfw' => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure unique slug
        while (Channel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $channel = Channel::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'allow_nsfw' => $request->boolean('allow_nsfw'),
        ]);

        // Update user relationship
        auth()->user()->update(['channel_id' => $channel->id]);

        return redirect()->route('channels.show', $channel->slug)
            ->with('success', 'Channel created successfully!');
    }

    /**
     * Display the specified channel.
     */
    public function show(Channel $channel, Request $request)
    {
        $channel->load(['user']);
        
        // Get channel's videos
        $videosQuery = $channel->videos()
            ->with(['channel.user'])
            ->where('is_published', true)
            ->where('visibility', 'public');

        // Filter by content type
        if ($request->get('tab') === 'nsfw' && $channel->allow_nsfw) {
            $videosQuery->where('is_nsfw', true);
        } else {
            $videosQuery->where('is_nsfw', false);
        }

        $videos = $videosQuery->latest()->paginate(20);

        // Check if current user is subscribed
        $isSubscribed = auth()->check() && 
            auth()->user()->subscriptions()->where('channel_id', $channel->id)->exists();

        // Check if this is the owner's channel
        $isOwner = auth()->check() && auth()->id() === $channel->user_id;

        return Inertia::render('channels/show', [
            'channel' => $channel,
            'videos' => $videos,
            'isSubscribed' => $isSubscribed,
            'isOwner' => $isOwner,
            'activeTab' => $request->get('tab', 'sfw'),
        ]);
    }

    /**
     * Show the form for editing the channel.
     */
    public function edit(Channel $channel)
    {
        // Check if user owns this channel
        if (auth()->id() !== $channel->user_id) {
            abort(403, 'You can only edit your own channel.');
        }

        return Inertia::render('channels/edit', [
            'channel' => $channel,
        ]);
    }

    /**
     * Update the specified channel.
     */
    public function update(Request $request, Channel $channel)
    {
        // Check if user owns this channel
        if (auth()->id() !== $channel->user_id) {
            abort(403, 'You can only update your own channel.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'allow_nsfw' => 'boolean',
        ]);

        $channel->update([
            'name' => $request->name,
            'description' => $request->description,
            'allow_nsfw' => $request->boolean('allow_nsfw'),
        ]);

        return redirect()->route('channels.show', $channel->slug)
            ->with('success', 'Channel updated successfully!');
    }

    /**
     * Remove the specified channel.
     */
    public function destroy(Channel $channel)
    {
        // Check if user owns this channel
        if (auth()->id() !== $channel->user_id) {
            abort(403, 'You can only delete your own channel.');
        }

        $channel->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Channel deleted successfully!');
    }
}