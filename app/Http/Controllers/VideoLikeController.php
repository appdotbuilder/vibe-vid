<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoLike;
use Illuminate\Http\Request;

class VideoLikeController extends Controller
{
    /**
     * Toggle like/dislike on a video.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'type' => 'required|in:like,dislike',
        ]);

        $video = Video::findOrFail($request->video_id);
        
        /** @var \App\Models\VideoLike|null $existingLike */
        $existingLike = auth()->user()->videoLikes()
            ->where('video_id', $video->id)
            ->first();

        if ($existingLike) {
            $currentType = $existingLike->type;
            if ($currentType === $request->type) {
                // Remove like/dislike if same type
                $existingLike->delete();
                
                if ($request->type === 'like') {
                    $video->decrement('likes_count');
                } else {
                    $video->decrement('dislikes_count');
                }
            } else {
                // Switch like to dislike or vice versa
                $existingLike->update(['type' => $request->type]);
                
                if ($currentType === 'like') {
                    $video->decrement('likes_count');
                    $video->increment('dislikes_count');
                } else {
                    $video->decrement('dislikes_count');
                    $video->increment('likes_count');
                }
            }
        } else {
            // Create new like/dislike
            auth()->user()->videoLikes()->create([
                'video_id' => $video->id,
                'type' => $request->type,
            ]);
            
            if ($request->type === 'like') {
                $video->increment('likes_count');
            } else {
                $video->increment('dislikes_count');
            }
        }

        return redirect()->back();
    }
}