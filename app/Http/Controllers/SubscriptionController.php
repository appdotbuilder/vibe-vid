<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Display user's subscriptions.
     */
    public function index()
    {
        $subscriptions = auth()->user()
            ->subscriptions()
            ->with(['channel.user'])
            ->latest()
            ->get();

        return Inertia::render('subscriptions/index', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Subscribe to a channel.
     */
    public function store(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
        ]);

        $channel = Channel::findOrFail($request->channel_id);

        // Prevent self-subscription
        if ($channel->user_id === auth()->id()) {
            return redirect()->back()
                ->withErrors(['subscription' => 'You cannot subscribe to your own channel.']);
        }

        // Check if already subscribed
        $existing = auth()->user()->subscriptions()
            ->where('channel_id', $channel->id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['subscription' => 'You are already subscribed to this channel.']);
        }

        // Create subscription
        auth()->user()->subscriptions()->create([
            'channel_id' => $channel->id,
            'notifications_enabled' => true,
        ]);

        // Update channel subscriber count
        $channel->increment('subscribers_count');

        return redirect()->back()
            ->with('success', 'Successfully subscribed to ' . $channel->name . '!');
    }

    /**
     * Unsubscribe from a channel.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
        ]);

        $channel = Channel::findOrFail($request->channel_id);

        $subscription = auth()->user()->subscriptions()
            ->where('channel_id', $channel->id)
            ->first();

        if (!$subscription) {
            return redirect()->back()
                ->withErrors(['subscription' => 'You are not subscribed to this channel.']);
        }

        $subscription->delete();

        // Update channel subscriber count
        $channel->decrement('subscribers_count');

        return redirect()->back()
            ->with('success', 'Successfully unsubscribed from ' . $channel->name . '.');
    }
}